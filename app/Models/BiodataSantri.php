<?php

namespace App\Models;

use App\Enums\BahasaMakna;
use App\Enums\GolonganDarah;
use App\Enums\HubunganWali;
use App\Enums\JenisKelamin;
use App\Enums\Kewarganegaraan;
use App\Enums\MulaiMengaji;
use App\Enums\PendidikanTerakhir;
use App\Enums\StatusKuliah;
use App\Enums\StatusOrangTua;
use App\Enums\StatusPernikahan;
use App\Enums\StatusPondok;
use App\Enums\StatusTinggal;
use App\Enums\UkuranBaju;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BiodataSantri extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'biodata_santri';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'tahun_pendaftaran',
        'nik',
        'tempat_lahir_id',
        'tanggal_lahir',
        'golongan_darah',
        'ukuran_baju',
        'pendidikan_terakhir',
        'program_studi',
        'universitas',
        'angkatan_kuliah',
        'status_kuliah',
        'tanggal_lulus_kuliah',
        'alamat',
        'kelurahan_id',
        'kecamatan_id',
        'kota_id',
        'provinsi_id',
        'asal_kelompok',
        'asal_desa',
        'asal_daerah',
        'mulai_mengaji',
        'bahasa_makna',
        'kewarganegaraan',
        'status_pernikahan',
        'status_tinggal',
        'status_orangtua',
        'anak_nomor',
        'jumlah_saudara',
        'nama_ayah',
        'nomor_telepon_ayah',
        'pekerjaan_ayah',
        'dapukan_ayah',
        'nama_ibu',
        'nomor_telepon_ibu',
        'pekerjaan_ibu',
        'dapukan_ibu',
        'nama_wali',
        'nomor_telepon_wali',
        'pekerjaan_wali',
        'dapukan_wali',
        'hubungan_wali',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tahun_pendaftaran' => 'integer',
        'tanggal_lahir' => 'date',
        'golongan_darah' => GolonganDarah::class,
        'ukuran_baju' => UkuranBaju::class,
        'pendidikan_terakhir' => PendidikanTerakhir::class,
        'angkatan_kuliah' => 'integer',
        'status_kuliah' => StatusKuliah::class,
        'tanggal_lulus_kuliah' => 'date',
        'kelurahan_id' => 'integer',
        'kecamatan_id' => 'integer',
        'kota_id' => 'integer',
        'provinsi_id' => 'integer',
        'mulai_mengaji' => MulaiMengaji::class,
        'bahasa_makna' => BahasaMakna::class,
        'kewarganegaraan' => Kewarganegaraan::class,
        'status_pernikahan' => StatusPernikahan::class,
        'status_tinggal' => StatusTinggal::class,
        'status_orangtua' => StatusOrangTua::class,
        'anak_nomor' => 'integer',
        'jumlah_saudara' => 'integer',
        'hubungan_wali' => HubunganWali::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function santri(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tempatLahir(): BelongsTo
    {
        return $this->belongsTo(Kota::class, 'tempat_lahir_id');
    }

    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id');
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function kota(): BelongsTo
    {
        return $this->belongsTo(Kota::class, 'kota_id');
    }

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

    protected function recordTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Biodata '.$this->user->nama_panggilan.' ('.$this->user->angkatan_pondok.')',
        );
    }

    public static function getForm(): array
    {
        return [
            Section::make('Biodata Santri')
                ->schema([
                    TextInput::make('tahun_pendaftaran')
                        ->label('Tahun Pendaftaran')
                        ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_user') && $operation != 'create')
                        ->required()
                        ->numeric(),
                    TextInput::make('nik')
                        ->label('Nomor Induk Kewarganegaraan')
                        ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_user') && $operation != 'create')
                        ->required()
                        ->type('number')
                        ->maxLength(16),
                    Select::make('tempat_lahir_id')
                        ->label('Tempat Lahir')
                        ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_user') && $operation != 'create')
                        ->relationship('tempatLahir', 'nama')
                        ->searchable()
                        ->required(),
                    DatePicker::make('tanggal_lahir')
                        ->label('Tanggal Lahir')
                        ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_user') && $operation != 'create')
                        ->required(),
                    Select::make('kewarganegaraan')
                        ->label('Kewarganegaraan')
                        ->required()
                        ->options(Kewarganegaraan::class),
                    Select::make('golongan_darah')
                        ->label('Golongan Darah')
                        ->required()
                        ->options(GolonganDarah::class),
                    Select::make('ukuran_baju')
                        ->label('Ukuran Baju')
                        ->required()
                        ->options(UkuranBaju::class),
                ])
                ->columns([
                    'sm' => 1,
                    'md' => 2,
                ]),

            Section::make('Pendidikan')
                ->schema([
                    Select::make('pendidikan_terakhir')
                        ->label('Pendidikan Terkahir')
                        ->required()
                        ->options(PendidikanTerakhir::class),

                    Cluster::make([
                            Select::make('program_studi_jenjang')
                                ->options([
                                    'S1' => 'S1',
                                    'S2' => 'S2',
                                    'S3' => 'S3',
                                    'D3' => 'D3',
                                    'D4' => 'D4',
                                    'Profesi' => 'Profesi',
                                ])
                                ->default('S1')
                                ->required(),
                            TextInput::make('program_studi')
                                ->label('Program Studi')
                                ->required()
                                ->columnSpan(7)
                                ->autocapitalize('words')
                                ->datalist(getProgramStudiList()),
                        ])
                        ->label('Program Studi')
                        ->columns(8),

                    TextInput::make('universitas')
                        ->label('Universitas')
                        ->required()
                        ->autocapitalize('words')
                        ->datalist(getUniversitasList()),

                    TextInput::make('angkatan_kuliah')
                        ->label('Angkatan Kuliah')
                        ->required()
                        ->numeric()
                        ->minValue(2011),

                    Select::make('status_kuliah')
                        ->label('Status Kuliah')
                        ->required()
                        ->options(StatusKuliah::class),

                    DatePicker::make('tanggal_lulus_kuliah')
                        ->label('Tanggal Lulus Kuliah'),
                ])
                ->columns([
                    'sm' => 1,
                    'md' => 2,
                ]),

            Section::make('Alamat Rumah')
                ->schema([
                    TextInput::make('alamat')
                        ->label('Alamat Lengkap')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(
                            ['md' => 2]
                        )
                        ->autocapitalize('words'),
                    Select::make('provinsi_id')
                        ->label('Provinsi')
                        ->options(Provinsi::all()->pluck('nama', 'id'))
                        ->searchable()
                        ->live()
                        ->required()
                        ->afterStateUpdated(function (Set $set) {
                            $set('kota_id', null);
                        }),
                    Select::make('kota_id')
                        ->label('Kota')
                        ->options(fn (Get $get) => Kota::where('provinsi_id', $get('provinsi_id'))->pluck('nama', 'id'))
                        ->searchable()
                        ->hidden(fn (Get $get) => $get('provinsi_id') == null)
                        ->live()
                        ->required()
                        ->afterStateUpdated(function (Set $set) {
                            $set('kecamatan_id', null);
                        }),
                    Select::make('kecamatan_id')
                        ->label('Kecamatan')
                        ->options(fn (Get $get) => Kecamatan::where('kota_id', $get('kota_id'))->pluck('nama', 'id'))
                        ->searchable()
                        ->hidden(fn (Get $get) => $get('kota_id') == null)
                        ->live()
                        ->required()
                        ->afterStateUpdated(function (Set $set) {
                            $set('kelurahan_id', null);
                        }),
                    Select::make('kelurahan_id')
                        ->label('Kelurahan')
                        ->options(fn (Get $get) => Kelurahan::where('kecamatan_id', $get('kecamatan_id'))->pluck('nama', 'id'))
                        ->searchable()
                        ->hidden(fn (Get $get) => $get('kecamatan_id') == null)
                        ->live()
                        ->required()
                ])
                ->columns([
                    'sm' => 1,
                    'md' => 2,
                ]),

            Section::make('Informasi Sambung')
                ->schema([
                    TextInput::make('asal_kelompok')
                        ->label('Asal Kelompok')
                        ->required()
                        ->maxLength(96)
                        ->autocapitalize('words'),
                    TextInput::make('asal_desa')
                        ->label('Asal Desa')
                        ->required()
                        ->maxLength(96)
                        ->autocapitalize('words'),
                    TextInput::make('asal_daerah')
                        ->label('Asal Daerah')
                        ->required()
                        ->maxLength(96)
                        ->autocapitalize('words'),
                    Select::make('mulai_mengaji')
                        ->label('Mulai Mengaji Sejak')
                        ->required()
                        ->options(MulaiMengaji::class),
                    Select::make('bahasa_makna')
                        ->label('Bahasa Dalam Makna')
                        ->required()
                        ->options(BahasaMakna::class),
                ])
                ->columns([
                    'sm' => 1,
                    'md' => 3,
                ]),

            Section::make('Keluarga')
                ->schema([
                    Select::make('status_pernikahan')
                        ->label('Status Pernikahan')
                        ->required()
                        ->options(StatusPernikahan::class),
                    Select::make('status_tinggal')
                        ->label('Status Tinggal')
                        ->required()
                        ->options(StatusTinggal::class),
                    Select::make('status_orangtua')
                        ->label('Status Orang Tua')
                        ->required()
                        ->options(StatusOrangTua::class),
                    TextInput::make('jumlah_saudara')
                        ->label('Jumlah Saudara')
                        ->required()
                        ->numeric(),
                    TextInput::make('anak_nomor')
                        ->label('Anak Nomor')
                        ->required()
                        ->numeric(),
                ])
                ->columns([
                    'sm' => 1,
                    'md' => 2,
                ]),

            Section::make('Orang Tua/Wali')
                ->schema([
                    TextInput::make('nama_ayah')
                        ->label('Nama Ayah')
                        ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_user') && $operation != 'create')
                        ->required(),
                    TextInput::make('nomor_telepon_ayah')
                        ->label('Nomor Telepon Ayah')
                        ->tel()
                        ->maxLength(16),
                    TextInput::make('pekerjaan_ayah')
                        ->label('Pekerjaan Ayah'),
                    TextInput::make('dapukan_ayah')
                        ->label('Dapukan Ayah'),

                    TextInput::make('nama_ibu')
                        ->label('Nama Ibu')
                        ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_user') && $operation != 'create')
                        ->required(),
                    TextInput::make('nomor_telepon_ibu')
                        ->label('Nomor Telepon Ibu')
                        ->tel()
                        ->maxLength(16),
                    TextInput::make('pekerjaan_ibu')
                        ->label('Pekerjaan Ibu'),
                    TextInput::make('dapukan_ibu')
                        ->label('Dapukan Ibu'),

                    TextInput::make('nama_wali')
                        ->label('Nama Wali'),
                    TextInput::make('nomor_telepon_wali')
                        ->label('Nomor Telepon Wali')
                        ->tel()
                        ->maxLength(16),
                    TextInput::make('pekerjaan_wali')
                        ->label('Pekerjaan Wali'),
                    TextInput::make('dapukan_wali')
                        ->label('Dapukan Wali'),
                    Select::make('hubungan_wali')
                        ->label('Hubungan Wali')
                        ->options(HubunganWali::class),
                ])
                ->columns([
                    'sm' => 1,
                    'md' => 2,
                ]),
        ];
    }
}

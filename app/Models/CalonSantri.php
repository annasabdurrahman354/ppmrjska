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
use App\Enums\StatusKuliahCalonSantri;
use App\Enums\StatusOrangTua;
use App\Enums\StatusPernikahan;
use App\Enums\StatusPerwalian;
use App\Enums\StatusTinggal;
use App\Enums\UkuranBaju;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CalonSantri extends Model implements HasMedia
{
    use InteractsWithMedia, HasUlids, SoftDeletes;

    protected $table = 'calon_santri';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'gelombang_pendaftaran_id',
        'nama',
        'nama_panggilan',
        'jenis_kelamin',
        'nomor_telepon',
        'email',
        'nik',
        'status_mubaligh',
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
        'status_mubaligh' => 'boolean',
        'jenis_kelamin' => JenisKelamin::class,
        'tanggal_lahir' => 'date',
        'golongan_darah' => GolonganDarah::class,
        'ukuran_baju' => UkuranBaju::class,
        'pendidikan_terakhir' => PendidikanTerakhir::class,
        'angkatan_kuliah' => 'integer',
        'status_kuliah' => StatusKuliahCalonSantri::class,
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

    public function penilaianCalonSantri(): HasOne
    {
        return $this->hasOne(PenilaianCalonSantri::class);
    }

    public function gelombangPendaftaran(): BelongsTo
    {
        return $this->belongsTo(GelombangPendaftaran::class, 'gelombang_pendaftaran_id');
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

    public function tempatLahir(): BelongsTo
    {
        return $this->belongsTo(Kota::class, 'tempat_lahir_id');
    }

    public static function getForm()
    {
        return [
            Section::make('Data Pribadi')
                ->schema([
                    Select::make('gelombang_pendaftaran_id')
                        ->label('Gelombang Pendaftaran')
                        ->required()
                        ->options(fn ($record): Collection =>
                            GelombangPendaftaran::all()->pluck('recordTitle', 'id')
                        )
                        ->disabledOn('edit')
                        ->searchable(),
                    TextInput::make('nama')
                        ->label('Nama')
                        ->required(),
                    TextInput::make('nama_panggilan')
                        ->label('Nama Panggilan')
                        ->required(),
                    Select::make('jenis_kelamin')
                        ->label('Jenis Kelamin')
                        ->options(JenisKelamin::class)
                        ->required(),
                    TextInput::make('nomor_telepon')
                        ->label('Nomor Telepon')
                        ->tel()
                        ->required(),
                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required(),
                    TextInput::make('nik')
                        ->label('Nomor Induk Kewarganegaraan')
                        ->required()
                        ->type('number')
                        ->maxLength(16),
                    Select::make('tempat_lahir_id')
                        ->label('Tempat Lahir')
                        ->searchable()
                        ->options(fn (Get $get) => Kota::pluck('nama', 'id'))
                        ->required(),
                    DatePicker::make('tanggal_lahir')
                        ->label('Tanggal Lahir')
                        ->required(),
                    ToggleButtons::make('status_mubaligh')
                        ->label('Status Mubaligh')
                        ->helperText('Apakah sudah mempunyai ijazah mubaligh?')
                        ->options([
                            false => 'Belum',
                            true => 'Sudah',
                        ])
                        ->colors([
                            false => 'primary',
                            true => 'warning',
                        ])
                        ->grouped()
                        ->inline()
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
                        ->options(StatusKuliahCalonSantri::class),
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
                        ->required()
                        ->searchable()
                        ->options(Provinsi::all()->pluck('nama', 'id'))
                        ->live()
                        ->afterStateUpdated(function (Set $set) {
                            $set('kota_id', null);
                        }),
                    Select::make('kota_id')
                        ->label('Kota')
                        ->required()
                        ->searchable()
                        ->options(fn (Get $get) => Kota::where('provinsi_id', $get('provinsi_id'))->pluck('nama', 'id'))
                        ->hidden(fn (Get $get) => $get('provinsi_id') == null)
                        ->live()
                        ->afterStateUpdated(function (Set $set) {
                            $set('kecamatan_id', null);
                        }),
                    Select::make('kecamatan_id')
                        ->label('Kecamatan')
                        ->required()
                        ->searchable()
                        ->options(fn (Get $get) => Kecamatan::where('kota_id', $get('kota_id'))->pluck('nama', 'id'))
                        ->hidden(fn (Get $get) => $get('kota_id') == null)
                        ->live()
                        ->afterStateUpdated(function (Set $set) {
                            $set('kelurahan_id', null);
                        }),
                    Select::make('kelurahan_id')
                        ->label('Kelurahan')
                        ->required()
                        ->searchable()
                        ->options(fn (Get $get) => Kelurahan::where('kecamatan_id', $get('kecamatan_id'))->pluck('nama', 'id'))
                        ->hidden(fn (Get $get) => $get('kecamatan_id') == null)
                        ->live()
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

                    Section::make('status_perwalian')
                        ->label('Wali Santri')
                        ->description('Isi data wali yang akan dimasukkan ke dalam grup wali santri!')
                        ->schema([
                            TextInput::make('nama_wali')
                                ->label('Nama Wali')
                                ->required(),
                            TextInput::make('nomor_telepon_wali')
                                ->label('Nomor Telepon Wali')
                                ->tel()
                                ->maxLength(16)
                                ->required(),
                            TextInput::make('pekerjaan_wali')
                                ->label('Pekerjaan Wali'),
                            TextInput::make('dapukan_wali')
                                ->label('Dapukan Wali'),
                            Select::make('hubungan_wali')
                                ->label('Hubungan Wali')
                                ->options(HubunganWali::class)
                                ->required(),
                        ])
                ])
                ->columns([
                    'sm' => 1,
                    'md' => 2,
                ]),
        ];
    }

    protected static function booted(): void
    {
        parent::boot();
        static::softDeleted(function ($record) {
            PenilaianCalonSantri::where('calon_santri_id', $record->id)->delete();
        });
    }
}

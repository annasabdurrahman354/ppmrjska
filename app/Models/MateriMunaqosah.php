<?php

namespace App\Models;

use App\Enums\JenisMateriMunaqosah;
use App\Enums\StatusPondok;
use Awcodes\Shout\Components\Shout;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\Colors\Color;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MateriMunaqosah extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'materi_munaqosah';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'angkatan_pondok',
        'semester',
        'tahun_ajaran',
        'jenis_materi',
        'materi',
        'detail',
        'hafalan',
        'indikator_materi',
        'indikator_hafalan',
        'dewan_guru_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'kelas' => 'string',
        'tahun_ajaran' => 'string',
        'jenis_materi' => JenisMateriMunaqosah::class,
        'materi' => 'array',
        'detail' => 'string',
        'semester' => 'integer',
        'hafalan' => 'array',
        'indikator_materi' => 'array',
        'indikator_hafalan' => 'array',
    ];

    public function dewanGuru(): BelongsTo
    {
        return $this->belongsTo(DewanGuru::class);
    }

    public function jadwalMunaqosah(): HasMany
    {
        return $this->hasMany(JadwalMunaqosah::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran', 'tahun_ajaran');
    }

    protected function recordTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Angkatan '.$this->angkatan_pondok. ' (Semester '.$this->semester.'): '.$this->jenis_materi->getLabel(),
        );
    }

    protected static function booted(): void
    {
        parent::boot();
        static::created(function (MateriMunaqosah $record) {
            TahunAjaran::firstOrCreate(
                ['tahun_ajaran' =>  $record->tahun_ajaran],
            );
        });
    }

    public static function getForm()
    {
        return [
            Section::make('Informasi Kelas')
                ->schema([
                    Select::make('angkatan_pondok')
                        ->label('Angkatan')
                        ->required()
                        ->disabledOn('edit')
                        ->options(
                            AngkatanPondok::get()
                                ->pluck('angkatan_pondok', 'angkatan_pondok')
                        ),

                    TextInput::make('semester')
                        ->required()
                        ->numeric()
                        ->maxValue(10),

                    Select::make('tahun_ajaran')
                        ->label('Tahun Ajaran')
                        ->options(TahunAjaran::all()->pluck('tahun_ajaran', 'tahun_ajaran'))
                        ->searchable()
                        ->preload()
                        ->createOptionForm(TahunAjaran::getForm())
                        ->createOptionUsing(function($data){
                            $tahunAjaran = new TahunAjaran();
                            $tahunAjaran->fill($data);
                            $tahunAjaran->save();
                            return $tahunAjaran->tahun_ajaran;
                        })
                        ->required(),

                    Select::make('dewan_guru_id')
                        ->label('Dewan Guru')
                        ->required()
                        ->searchable()
                        ->relationship('dewanGuru', 'nama'),
                ]),
            Section::make('Materi Munaqosah')
                ->schema([
                    ToggleButtons::make('jenis_materi')
                        ->label('Jenis Materi')
                        ->required()
                        ->inline()
                        ->options(JenisMateriMunaqosah::class)
                        ->default(MateriSurat::class)
                        ->live()
                        ->afterStateUpdated(function(Set $set) {
                            $set('materi', null);
                        }),

                    Select::make('materi')
                        ->label('Pilih Materi')
                        ->placeholder('Bisa lebih dari satu.')
                        ->hidden(fn (Get $get) => $get('jenis_materi') == null)
                        ->multiple()
                        ->getSearchResultsUsing(fn (Get $get, string $search): array =>
                        $get('jenis_materi')::where('nama', 'like', "%{$search}%")
                            ->limit(20)
                            ->orderBy('nama')
                            ->pluck('nama', 'nama')
                            ->toArray(),
                        )
                        ->getOptionLabelUsing(fn (Get $get, $values): ?string =>
                        $get('jenis_materi')::whereIn('nama', $values)->pluck('nama', 'nama')->toArray()
                        )
                        ->hidden(function (Get $get) {
                            return $get('jenis_materi') == MateriHafalan::class;
                        })
                        ->disabled(function (Get $get) {
                            return $get('jenis_materi') == MateriHafalan::class;
                        })
                        ->required(function (Get $get) {
                            return $get('jenis_materi') != MateriHafalan::class;
                        })
                        ->live(),

                    TextInput::make('detail')
                        ->placeholder('Tuliskan detail materi yang akan diujikan.')
                        ->maxLength(255)
                        ->default(null)
                        ->hidden(function (Get $get) {
                            return $get('jenis_materi') == MateriHafalan::class;
                        })
                        ->disabled(function (Get $get) {
                            return $get('jenis_materi') == MateriHafalan::class;
                        }),

                    Select::make('hafalan')
                        ->label('Pilih Hafalan')
                        ->placeholder('Bisa lebih dari satu.')
                        ->multiple()
                        ->getSearchResultsUsing(fn (string $search): array =>
                        MateriHafalan::where('nama', 'like', "%{$search}%")
                            ->limit(20)
                            ->orderBy('nama')
                            ->pluck('nama', 'nama')
                            ->toArray(),
                        )
                        ->getOptionLabelUsing(fn ($values): ?string =>
                        MateriHafalan::whereIn('nama', $values)->pluck('nama', 'nama')->toArray()
                        )
                        ->required(function (Get $get) {
                            return $get('jenis_materi') == MateriHafalan::class;
                        })
                        ->live(),

                    TagsInput::make('indikator_materi')
                        ->label('Indikator Penilaian Materi')
                        ->hidden(function (Get $get) {
                            return empty($get('materi'));
                        })
                        ->disabled(function (Get $get) {
                            return empty($get('materi'));
                        })
                        ->required(function (Get $get) {
                            return !empty($get('materi'));
                        })
                        ->placeholder('Tuliskan indikator penilaian materi.'),

                    TagsInput::make('indikator_hafalan')
                        ->label('Indikator Penilaian Hafalan')
                        ->hidden(function (Get $get) {
                            return empty($get('hafalan'));
                        })
                        ->disabled(function (Get $get) {
                            return empty($get('hafalan'));
                        })
                        ->required(function (Get $get) {
                            return !empty($get('hafalan'));
                        })
                        ->placeholder('Tuliskan indikator penilaian hafalan.'),
                ]),

            Section::make('Jadwal Munaqosah')
                ->schema([
                    Shout::make('st-empty')
                        ->content('Belum ada jadwal munaqosah untuk materi ini!')
                        ->type('info')
                        ->color(Color::Yellow)
                        ->visible(fn(Get $get) => !filled($get('jadwalMunaqosah'))),
                    TableRepeater::make('jadwalMunaqosah')
                        ->hiddenLabel()
                        ->addable()
                        ->addActionLabel('+ Tambah Jadwal')
                        ->deletable()
                        ->relationship('jadwalMunaqosah')
                        ->headers([
                            Header::make('Waktu Munaqosah'),
                            Header::make('Maksimal Pendaftar'),
                            Header::make('Batas Awal Pendfataran'),
                            Header::make('Batas Akhir Pendaftaran'),
                            Header::make('Pendaftar')
                        ])
                        ->schema([
                            DateTimePicker::make('waktu')
                                ->label('Waktu Munaqosah')
                                ->distinct()
                                ->required(),
                            TextInput::make('maksimal_pendaftar')
                                ->label('Maksimal Pendaftar')
                                ->required()
                                ->numeric(),
                            DateTimePicker::make('batas_awal_pendaftaran')
                                ->label('Batas Awal Pendaftaran')
                                ->beforeOrEqual('batas_akhir_pendaftaran')
                                ->required(),
                            DateTimePicker::make('batas_akhir_pendaftaran')
                                ->label('Batas Akhir Pendaftaran')
                                ->afterOrEqual('batas_awal_pendaftaran')
                                ->beforeOrEqual('waktu')
                                ->required(),
                            TableRepeater::make('plotJadwalMunaqosah')
                                ->relationship('plotJadwalMunaqosah')
                                ->streamlined()
                                ->renderHeader(false)
                                ->default([])
                                ->maxItems(fn (Get $get) => $get('maksimal_pendaftar'))
                                ->headers([
                                    Header::make('Santri'),
                                ])
                                ->schema([
                                    Select::make('user_id')
                                        ->options(fn (Get $get) =>
                                            User::whereAngkatan($get('../../../../angkatan_pondok'))
                                                ->whereNotIn('status_pondok', [StatusPondok::NONAKTIF, StatusPondok::KELUAR, StatusPondok::LULUS])
                                                ->whereNull('tanggal_lulus_pondok')
                                                ->get()
                                                ->pluck('nama', 'id')
                                                ->toArray()
                                        )
                                        ->preload()
                                        ->searchable()
                                        ->required(),
                                ])
                                ->addActionLabel('+ Pendaftar')
                        ])
                ])
        ];
    }
}

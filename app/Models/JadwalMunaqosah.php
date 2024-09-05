<?php

namespace App\Models;

use App\Enums\StatusPondok;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalMunaqosah extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'jadwal_munaqosah';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'materi_munaqosah_id',
        'waktu',
        'maksimal_pendaftar',
        'batas_awal_pendaftaran',
        'batas_akhir_pendaftaran',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'waktu' => 'timestamp:d-m-Y H:00',
        'maksimal_pendaftar' => 'integer',
        'batas_awal_pendaftaran' => 'timestamp:d-m-Y H:00',
        'batas_akhir_pendaftaran' => 'timestamp:d-m-Y H:00',
    ];

    public function materiMunaqosah(): BelongsTo
    {
        return $this->belongsTo(MateriMunaqosah::class);
    }

    public function plotJadwalMunaqosah(): HasMany
    {
        return $this->hasMany(PlotJadwalMunaqosah::class);
    }

    protected function recordTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Jadwal Munaqosah '.$this->materiMunaqosah->kelas. ' (Semester '.$this->materiMunaqosah->semester.'): '.$this->materiMunaqosah->jenis_materi->getLabel().' ('.(string) $this->waktu.')',
        );
    }

    protected function recordTitleCalendar(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Kelas ' . $this->materiMunaqosah->kelas. ' (Semsester '.$this->materiMunaqosah->semester.'): '.$this->materiMunaqosah->jenis_materi->getLabel()
        );
    }

    public static function getForm()
    {
        return [
            Section::make('Detail Munaqosah')
                ->schema([
                    Select::make('materi_munaqosah_id')
                        ->label('Materi Munaqosah')
                        ->options(MateriMunaqosah::all()->pluck('recordTitle', 'id'))
                        ->searchable()
                        ->preload()
                        ->columnSpanFull()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function(Set $set) {
                            $set('plotJadwalMunaqosah', []);
                        }),
                    TextInput::make('maksimal_pendaftar')
                        ->label('Maksimal Pendaftar')
                        ->default(8)
                        ->required()
                        ->numeric()
                        ->live(),
                    DateTimePicker::make('waktu')
                        ->label('Waktu Munaqosah')
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn (Set $set, $state) => $set('batas_akhir_pendaftaran', Carbon::parse($state)->subDay())),
                    DateTimePicker::make('batas_awal_pendaftaran')
                        ->label('Batas Mulai Pendaftaran')
                        ->beforeOrEqual('batas_akhir_pendaftaran')
                        ->default(now())
                        ->required(),
                    DateTimePicker::make('batas_akhir_pendaftaran')
                        ->label('Batas Akhir Pendaftaran')
                        ->beforeOrEqual('waktu')
                        ->required(),
                ]),

            Section::make('Plot Jadwal Munaqosah')
                ->schema([
                    TableRepeater::make('plotJadwalMunaqosah')
                        ->hiddenLabel()
                        ->relationship('plotJadwalMunaqosah')
                        ->default([])
                        ->disabled(fn (Get $get) => !filled($get('materi_munaqosah_id')))
                        ->headers([
                            Header::make('Santri'),
                            Header::make('Status Terlaksana')
                        ])
                        ->schema([
                            Select::make('user_id')
                                ->hiddenLabel()
                                ->required()
                                ->searchable()
                                ->preload()
                                ->placeholder('Pilih santri sesuai kelas munaqosah...')
                                ->getSearchResultsUsing(function (string $search, Get $get): array{
                                    $materiMunaqosah = MateriMunaqosah::where('id', $get('../../materi_munaqosah_id'))->first();
                                    $kelas = $materiMunaqosah->kelas ?? ['a'];
                                    return User::where('nama', 'like', "%{$search}%")
                                        ->whereKelas($kelas)
                                        ->whereNotIn('status_pondok', [StatusPondok::NONAKTIF, StatusPondok::KELUAR, StatusPondok::LULUS])
                                        ->whereNull('tanggal_lulus_pondok')
                                        ->limit(20)
                                        ->pluck('nama', 'id')
                                        ->toArray();
                                })
                                ->getOptionLabelUsing(fn ($value): ?string => User::find($value)?->nama),
                            Toggle::make('status_terlaksana')
                                ->label('Terlaksana?')
                                ->default(false)
                                ->required(),
                        ])
                        ->addable()
                        ->addActionLabel('Tambah Pendaftar +')
                        ->maxItems(fn (Get $get) => $get('maksimal_pendaftar'))
                        ->live()
                ])
        ];
    }

    public static function getInfolist()
    {
        return [
            TextEntry::make('materiMunaqosah.recordTitle')
                ->label('Materi Munaqosah'),
            TextEntry::make('waktu')
                ->label('Waktu Munaqosah')
                ->dateTime(),
            TextEntry::make('maksimal_pendaftar')
                ->label('Maksimal Pendaftar')
                ->numeric(),
            TextEntry::make('batas_awal_pendaftaran')
                ->label('Batas Awal Pendaftaran')
                ->dateTime(),
            TextEntry::make('batas_akhir_pendaftaran')
                ->label('Batas Akhir Pendaftaran')
                ->dateTime(),
            RepeatableEntry::make('plotJadwalMunaqosah')
                ->label('Pendaftar Jadwal Munaqosah')
                ->schema([
                    TextEntry::make('user.nama'),
                    TextEntry::make('status_terlaksana')
                        ->label('Status Terlaksana')
                        ->suffixAction(
                            \Filament\Infolists\Components\Actions\Action::make('ubahStatusTerlaksana')
                                ->icon('heroicon-m-clipboard')
                                ->requiresConfirmation()
                                ->action(function ($record) {
                                    $record->status_terlaksana = !$record->status_terlaksana;
                                    $record->save();
                                })
                        ),
                ])
        ];
    }
}

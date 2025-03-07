<?php

namespace App\Models;

use App\Enums\JenisMateriMunaqosah;
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
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\TextColumn;
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
            get: fn () => $this->materiMunaqosah->recordTitle.': '.(string) $this->waktu,
        );
    }

    protected function jumlahPendaftar(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->plotJadwalMunaqosah()->count(),
        );
    }

    protected function hafalan(): Attribute
    {
        return Attribute::make(
            get: fn () => implode(', ', $this->materiMunaqosah->hafalan),
        );
    }

    protected function dewanGuru(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->materiMunaqosah->dewanGuru->nama_panggilan,
        );
    }

    protected function recordTitleCalendar(): Attribute
    {
        return Attribute::make(
            get: function () {
                if($this->materiMunaqosah->jenis_materi == JenisMateriMunaqosah::HAFALAN){
                    return 'Materi Hafalan -  Semester ' . $this->materiMunaqosah->semester;
                }
                return implode(',', $this->materiMunaqosah->materi).'- Semester '.$this->materiMunaqosah->semester;
            },
        );
    }

    protected function recordTitleCalendarResource(): Attribute
    {
        return Attribute::make(
            get: function () {
                if($this->materiMunaqosah->jenis_materi == JenisMateriMunaqosah::HAFALAN){
                    return 'Hafalan (A.'. $this->materiMunaqosah->angkatan_pondok . '-S.' . $this->materiMunaqosah->semester.')';
                }
                return implode(',', $this->materiMunaqosah->materi).' (A.'.$this->materiMunaqosah->angkatan_pondok.'-S.'.$this->materiMunaqosah->semester.')';
            },
        );
    }

    public static function getColumns()
    {
        return [
            TextColumn::make('id')
                ->label('ID')
                ->toggleable(isToggledHiddenByDefault: true)
                ->searchable(),
            TextColumn::make('materiMunaqosah.angkatan_pondok')
                ->label('Angkatan')
                ->searchable()
                ->sortable(),
            TextColumn::make('materiMunaqosah.materi')
                ->label('Materi Munaqosah')
                ->badge()
                ->searchable(),
            TextColumn::make('materiMunaqosah.hafalan')
                ->label('Materi Hafalan')
                ->badge()
                ->listWithLineBreaks()
                ->limitList(3)
                ->expandableLimitedList()
                ->searchable(),
            TextColumn::make('waktu')
                ->label('Waktu Munaqosah')
                ->dateTime()
                ->sortable(),
            TextColumn::make('maksimal_pendaftar')
                ->label('Maks Pendaftar')
                ->numeric()
                ->sortable(),
            TextColumn::make('total_plotjadwalmunaqosah')
                ->label('Pendaftar')
                ->numeric(),
            TextColumn::make('terlaksana_plotjadwalmunaqosah')
                ->label('Terlaksana')
                ->numeric(),
            TextColumn::make('batas_awal_pendaftaran')
                ->label('Batas Mulai Pendaftaran')
                ->dateTime()
                ->sortable(),
            TextColumn::make('batas_akhir_pendaftaran')
                ->label('Batas Akhir Pendaftaran')
                ->dateTime()
                ->sortable(),
            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
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

            Section::make('Pendaftar Jadwal Munaqosah')
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
                                    $angkatan_pondok = $materiMunaqosah->angkatan_pondok ?? ['a'];
                                    return User::where('nama', 'like', "%{$search}%")
                                        ->whereAngkatan($angkatan_pondok)
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
                    IconEntry::make('status_terlaksana')
                        ->label('Status Terlaksana')
                        ->boolean()
                        ->action(
                            \Filament\Infolists\Components\Actions\Action::make('ubahStatusTerlaksana')
                                ->icon('heroicon-m-clipboard')
                                ->requiresConfirmation()
                                ->action(function ($record) {
                                    $record->status_terlaksana = !$record->status_terlaksana;
                                    $record->save();
                                })
                        ),
                ])
                ->registerActions([
                    \Filament\Infolists\Components\Actions\Action::make('ingatkanMunaqosah')
                        ->label('Ingatkan')
                        ->icon('heroicon-m-chat-bubble-left-ellipsis')
                        ->url(function ($record) {
                            $jadwal = $record->jadwalMunaqosah;
                            return $record->user->ingatkanMunaqosah($jadwal);
                        })
                ])
        ];
    }
}

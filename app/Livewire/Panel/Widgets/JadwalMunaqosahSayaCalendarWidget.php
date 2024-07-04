<?php

namespace App\Livewire\Panel\Widgets;

use App\Enums\StatusPondok;
use App\Models\JadwalMunaqosah;
use App\Models\MateriMunaqosah;
use App\Models\PlotJadwalMunaqosah;
use App\Models\User;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Actions\ViewAction;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class JadwalMunaqosahSayaCalendarWidget extends FullCalendarWidget
{
    public Model | string | null $model = JadwalMunaqosah::class;

    public function config(): array
    {
        return [
            'initialView' => 'dayGridMonth',
            'firstDay' => 1,
            'headerToolbar' => [
                'left' => 'prev,next,today',
                'center' => 'title',
                'right' => 'dayGridMonth,dayGridWeek,listWeek',
            ],
        ];
    }

    protected function getFormModel(): Model|string|null
    {
        return $this->event ?? JadwalMunaqosah::class;
    }

    public function fetchEvents(array $fetchInfo): array
    {
        return JadwalMunaqosah::query()
            ->whereHas('materiMunaqosah', function ($query) {
                $query->where('kelas', auth()->user()->kelas);
            })
            ->where('waktu', '>=', $fetchInfo['start'])
            ->where('waktu', '<=', $fetchInfo['end'])
            ->get()
            ->map(function (JadwalMunaqosah $jadwalMunaqosah) {
                $materiMunaqosah = $jadwalMunaqosah->materiMunaqosah;

                $isTelahMelaksanakan = $materiMunaqosah->jadwalmunaqosah()
                    ->where('waktu', '<', now())
                    ->whereHas('plotJadwalMunaqosah', fn($query) => $query->where('user_id', auth()->id)->where('status_terlaksana', true))
                    ->exists();

                $isSudahAmbilBelumMelaksanakan = $materiMunaqosah->jadwalmunaqosah()
                    ->where('waktu', '>', now())
                    ->whereHas('plotJadwalMunaqosah', fn($query) => $query->where('user_id', auth()->id)->where('status_terlaksana', false))
                    ->exists();

                $isLewatJadwal = $jadwalMunaqosah->waktu < now();

                $isLewatJadwalPendaftaran = $jadwalMunaqosah->batas_akhir_pendaftaran < now();

                $color = ($isTelahMelaksanakan || $isSudahAmbilBelumMelaksanakan || $isLewatJadwal || $isLewatJadwalPendaftaran) ? 'red' : 'green';

                return [
                    'id' => $jadwalMunaqosah->id,
                    'title' => $jadwalMunaqosah->recordTitleCalendar,
                    'start' => $jadwalMunaqosah->waktu,
                    //'url' => JadwalMunaqosahResource::getUrl(name: 'view', parameters: ['record' => $jadwalMunaqosah]),
                    //'shouldOpenUrlInNewTab' => true,
                    'color' => $color,
                ];
            })
            ->all();
    }

    protected function modalActions(): array
    {
        return [
            Action::make('ambilJadwal')
                ->hidden(function (JadwalMunaqosah $record) {
                    $materiMunaqosah = $record->materiMunaqosah;

                    $isTelahMelaksanakan = $materiMunaqosah->jadwalmunaqosah()
                        ->where('waktu', '<', now())
                        ->whereHas('plotJadwalMunaqosah', function ($query) {
                            $query->where('user_id', auth()->id)->where('status_terlaksana', true);
                        })
                        ->exists();

                    $isSudahAmbilBelumMelaksanakan = $materiMunaqosah->jadwalmunaqosah()
                        ->where('waktu', '>', now())
                        ->whereHas('plotJadwalMunaqosah', function ($query) {
                            $query->where('user_id', auth()->id)->where('status_terlaksana', false);
                        })
                        ->exists();

                    $isLewatJadwal = $record->waktu < now();
                    $isLewatJadwalPendaftaran = $record->batas_akhir_pendaftaran < now();
                    $kelasSasaran = $materiMunaqosah->kelas === auth()->user()->kelas;

                    if ($isTelahMelaksanakan || $isSudahAmbilBelumMelaksanakan || $isLewatJadwal || $isLewatJadwalPendaftaran || !$kelasSasaran) {
                        return true;
                    }
                    return false;
                })
                ->action(function (JadwalMunaqosah $record): void {
                    $plotMunaqosah = PlotJadwalMunaqosah::create([
                        'jadwal_munaqosah_id' => $record->id,
                        'user_id' => auth()->id(),
                        'status_terlaksana' => false
                    ]);
                    $plotMunaqosah->save();
                })
                ->requiresConfirmation()
                ->color('warning')
                ->modalIcon('heroicon-o-pencil-square')
                ->modalIconColor('warning')
        ];
    }

    protected function viewAction(): Action
    {
        return ViewAction::make()
            ->form($this->getFormSchema())
            ->slideOver();
    }

    public function getFormSchema(): array
    {
        return [
            Section::make('Detail Munaqosah')
                ->columns(2)
                ->schema([
                    Select::make('materi_munaqosah_id')
                        ->label('Materi Munaqosah')
                        ->options(MateriMunaqosah::all()->pluck('recordTitle', 'id'))
                        ->searchable()
                        ->columnSpanFull()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function(Set $set) {
                            $set('plotJadwalMunaqosah', []);
                        }),
                    DateTimePicker::make('waktu')
                        ->label('Waktu Munaqosah')
                        ->required(),

                    DateTimePicker::make('batas_awal_pendaftaran')
                        ->label('Batas Mulai Pendaftaran')
                        ->required(),
                    DateTimePicker::make('batas_akhir_pendaftaran')
                        ->label('Batas Akhir Pendaftaran')
                        ->required(),

                    TextInput::make('maksimal_pendaftar')
                        ->label('Maksimal Pendaftar')
                        ->default(8)
                        ->required()
                        ->numeric()
                        ->minValue(fn (Get $get) => count($get('plotJadwalMunaqosah')))
                        ->live(),
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
                                    return User::where('kelas', $kelas)
                                        ->where('nama', 'like', "%{$search}%")
                                        ->where('status_pondok', StatusPondok::AKTIF->value)
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
}

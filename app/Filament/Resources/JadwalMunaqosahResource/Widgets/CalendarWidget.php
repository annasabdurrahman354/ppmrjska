<?php

namespace App\Filament\Resources\JadwalMunaqosahResource\Widgets;

use App\Enums\StatusPondok;
use App\Filament\Resources\JadwalMunaqosahResource\Pages\ListJadwalMunaqosahs;
use App\Models\JadwalMunaqosah;
use App\Models\MateriMunaqosah;
use App\Models\User;
use Awcodes\Shout\Components\Shout;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\Colors\Color;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Saade\FilamentFullCalendar\Actions\DeleteAction;
use Saade\FilamentFullCalendar\Actions\EditAction;
use Saade\FilamentFullCalendar\Actions\ViewAction;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    use InteractsWithPageFilters;

    public Model | string | null $model = JadwalMunaqosah::class;

    protected function getFormModel(): Model|string|null
    {
        return $this->event ?? JadwalMunaqosah::class;
    }

    public function resolveEventRecord(array $data): JadwalMunaqosah
    {
        return JadwalMunaqosah::where('id', $data['id']);
    }

    protected function getTablePage(): string
    {
        return ListJadwalMunaqosahs::class;
    }

    public function fetchEvents(array $fetchInfo): array
    {
        return JadwalMunaqosah::query()
            ->where('waktu', '>=', $fetchInfo['start'])
            ->where('waktu', '<=', $fetchInfo['end'])
            ->get()
            ->map(
                fn (JadwalMunaqosah $jadwalMunaqosah) => [
                    'id' => $jadwalMunaqosah->id,
                    'title' => $jadwalMunaqosah->recordTitleCalendar,
                    'start' => $jadwalMunaqosah->waktu,
                    //'url' => JadwalMunaqosahResource::getUrl(name: 'view', parameters: ['record' => $jadwalMunaqosah]),
                    //'shouldOpenUrlInNewTab' => true,
                    'color' => $jadwalMunaqosah->waktu < now() ? 'red' : 'green'
                ]
            )
            ->all();
    }

    protected function headerActions(): array
    {
        return [
            CreateAction::make()
                ->mountUsing(
                    function (Form $form, array $arguments) {
                        $form->fill([
                            'materi_munaqosah_id' => null,
                            'waktu' => $arguments['start'] ?? null,
                            'maksimal_pendaftar' => 8,
                            'batas_awal_pendaftaran' => null,
                            'batas_akhir_pendaftaran' => null,
                            'plotJadwalMunaqosah' => []
                        ]);
                    }
                )
                ->slideOver()
        ];
    }

    protected function modalActions(): array
    {
        return [
            EditAction::make()
                ->mountUsing(
                    function (JadwalMunaqosah $record, Form $form, array $arguments) {
                        $form->fill([
                            'materi_munaqosah_id' => $record->materi_munaqosah_id,
                            'waktu' => $arguments['event']['start'] ?? $record->waktu,
                            'maksimal_pendaftar' => $record->maksimal_pendaftar,
                            'batas_awal_pendaftaran' => $record->batas_awal_pendaftaran,
                            'batas_akhir_pendaftaran' => $record->batas_akhir_pendaftaran,
                            'plotJadwalMunaqosah' => $record->plotJadwalMunaqosah->toArray()
                        ]);
                    }
                )
                ->slideOver()
                ->closeModalByClickingAway(false),
            DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Hapus Jadwal Munaqosah')
                ->modalDescription('Apakah Anda yakin untuk menghapus?')
                ->modalSubmitActionLabel('Ya')
                ->modalCancelActionLabel('Batal')
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
                    TextInput::make('maksimal_pendaftar')
                        ->label('Maksimal Pendaftar')
                        ->default(8)
                        ->required()
                        ->numeric()
                        ->minValue(fn (Get $get) => count($get('plotJadwalMunaqosah')))
                        ->live(),
                    DateTimePicker::make('batas_awal_pendaftaran')
                        ->label('Batas Mulai Pendaftaran')
                        ->required(),
                    DateTimePicker::make('batas_akhir_pendaftaran')
                        ->label('Batas Akhir Pendaftaran')
                        ->required(),
                ]),

            Section::make('Plot Jadwal Munaqosah')
                ->schema([
                    Shout::make('st-empty')
                        ->content('Belum ada pendaftar!')
                        ->type('info')
                        ->color(Color::Yellow)
                        ->visible(fn(Get $get) => !filled($get('plotJadwalMunaqosah'))),
                    Repeater::make('plotJadwalMunaqosah')
                        ->hiddenLabel()
                        ->relationship('plotJadwalMunaqosah')
                        ->default([])
                        ->schema([
                            Select::make('user_id')
                                ->hiddenLabel()
                                ->required()
                                ->disabled(fn(Get $get) => !filled($get('../../materi_munaqosah_id')))
                                ->searchable()
                                ->preload()
                                ->placeholder('Pilih santri sesuai kelas munaqosah...')
                                ->getSearchResultsUsing(function (string $search, Get $get): array{
                                    $materiMunaqosah = MateriMunaqosah::where('id', $get('../../materi_munaqosah_id'));
                                    $kelas = $materiMunaqosah->kelas;
                                    return User::where('kelas', $kelas)
                                        ->where('nama', 'like', "%{$search}%")
                                        ->where('status_pondok', StatusPondok::AKTIF->value)
                                        ->where('tanggal_lulus_pondok', null)
                                        ->limit(20)
                                        ->pluck('nama', 'id')
                                        ->toArray();
                                })
                                ->getOptionLabelUsing(fn ($value): ?string => User::find($value)?->nama)
                                ->columnSpan(4),
                            Toggle::make('status_terlaksana')
                                ->label('Terlaksana?')
                                ->default(false)
                                ->required()
                                ->columnSpan(1),
                        ])
                        ->columns(5)
                        ->addable()
                        ->addActionLabel('Tambah Pendaftar +')
                        ->maxItems(fn (Get $get) => (int) $get('maksimal_pendaftar'))
                        ->live()
                ])
        ];
    }
}

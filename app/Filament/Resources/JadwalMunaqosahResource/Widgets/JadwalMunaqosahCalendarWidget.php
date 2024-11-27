<?php

namespace App\Filament\Resources\JadwalMunaqosahResource\Widgets;

use App\Enums\StatusPondok;
use App\Filament\Resources\JadwalMunaqosahResource\Pages\ListJadwalMunaqosahs;
use App\Models\JadwalMunaqosah;
use App\Models\MateriMunaqosah;
use App\Models\PlotJadwalMunaqosah;
use App\Models\User;
use Awcodes\Shout\Components\Shout;
use Faker\Provider\ar_EG\Text;
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
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Colors\Color;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Saade\FilamentFullCalendar\Actions\DeleteAction;
use Saade\FilamentFullCalendar\Actions\EditAction;
use Saade\FilamentFullCalendar\Actions\ViewAction;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class JadwalMunaqosahCalendarWidget extends FullCalendarWidget
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
                    'title' => $jadwalMunaqosah->recordTitleCalendarResource,
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
                ->visible(can('create_jadwal::munaqosah'))
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
                ->visible(can('update_jadwal::munaqosah'))
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
                ->visible(can('delete_jadwal::munaqosah') || can('delete_any_jadwal::munaqosah'))
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
            ->infolist(JadwalMunaqosah::getInfolist())
            ->slideOver();
    }

    public function getFormSchema(): array
    {
        return JadwalMunaqosah::getForm();
    }
}

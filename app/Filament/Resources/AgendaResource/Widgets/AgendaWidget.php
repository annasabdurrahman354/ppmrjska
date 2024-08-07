<?php

namespace App\Filament\Resources\AgendaResource\Widgets;

use App\Filament\Resources\AgendaResource\Pages\ListAgenda;
use App\Models\Agenda;
use App\Models\JadwalMunaqosah;
use Filament\Actions\Action;
use Filament\Forms\Form;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Saade\FilamentFullCalendar\Actions\DeleteAction;
use Saade\FilamentFullCalendar\Actions\EditAction;
use Saade\FilamentFullCalendar\Actions\ViewAction;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class AgendaWidget extends FullCalendarWidget
{
    use InteractsWithPageFilters;

    public Model | string | null $model = Agenda::class;

    protected function getFormModel(): Model|string|null
    {
        return $this->event ?? Agenda::class;
    }

    public function resolveEventRecord(array $data): Agenda
    {
        return Agenda::where('id', $data['id']);
    }

    protected function getTablePage(): string
    {
        return ListAgenda::class;
    }

    public function fetchEvents(array $fetchInfo): array
    {
        return Agenda::query()
            ->where('tanggal_awal', '>=', $fetchInfo['start'])
            ->where('tanggal_awal', '<=', $fetchInfo['end'])
            ->get()
            ->map(
                fn (Agenda $agenda) => [
                    'id' => $agenda->id,
                    'title' => $agenda->recordTitle,
                    'start' => $agenda->tanggal_awal,
                    'end' => $agenda->tanggal_akhir ?: $agenda->tanggal_awal,
                    //'url' => JadwalMunaqosahResource::getUrl(name: 'view', parameters: ['record' => $jadwalMunaqosah]),
                    //'shouldOpenUrlInNewTab' => true,
                    'color' => $agenda->tanggal_akhir < now() ? 'red' : 'green'
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
                            'tanggal_awal' => $arguments['start'] ?? null,
                            'tanggal_akhir' => $arguments['start'] ?? null,
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
                            'tanggal_awal' => $arguments['event']['start'] ?? $record->tanggal_awal,
                            'tanggal_akhir' => $arguments['event']['start'] ?? $record->tanggal_akhir,
                        ]);
                    }
                )
                ->slideOver()
                ->closeModalByClickingAway(false),
            DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Hapus Agenda')
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
        return Agenda::getForm();
    }
}

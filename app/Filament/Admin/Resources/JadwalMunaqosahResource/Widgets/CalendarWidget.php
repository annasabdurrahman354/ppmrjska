<?php

namespace App\Filament\Admin\Resources\JadwalMunaqosahResource\Widgets;

use App\Filament\Admin\Resources\JadwalMunaqosahResource;
use App\Filament\Admin\Resources\JadwalMunaqosahResource\Pages\ListJadwalMunaqosahs;
use App\Models\JadwalMunaqosah;
use Saade\FilamentFullCalendar\Data\EventData;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    /**
     * FullCalendar will call this function whenever it needs new event data.
     * This is triggered when the user clicks prev/next or switches views on the calendar.
     */

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
                fn (JadwalMunaqosah $jadwalMunaqosah) => EventData::make()
                    ->id($jadwalMunaqosah->id)
                    ->title($jadwalMunaqosah->recordTitleCalendar)
                    ->start($jadwalMunaqosah->waktu)
                    ->url(
                        url: JadwalMunaqosahResource::getUrl(name: 'view', parameters: ['record' => $jadwalMunaqosah]),
                        shouldOpenUrlInNewTab: true
                    )
            )->toArray();
    }
}
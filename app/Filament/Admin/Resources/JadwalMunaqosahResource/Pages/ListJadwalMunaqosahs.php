<?php

namespace App\Filament\Admin\Resources\JadwalMunaqosahResource\Pages;

use App\Filament\Admin\Resources\JadwalMunaqosahResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListJadwalMunaqosahs extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = JadwalMunaqosahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return JadwalMunaqosahResource::getWidgets();
    }
}

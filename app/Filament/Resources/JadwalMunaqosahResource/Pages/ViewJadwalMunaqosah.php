<?php

namespace App\Filament\Resources\JadwalMunaqosahResource\Pages;

use App\Filament\Resources\JadwalMunaqosahResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewJadwalMunaqosah extends ViewRecord
{
    protected static string $resource = JadwalMunaqosahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

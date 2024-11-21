<?php

namespace App\Filament\Resources\MateriMunaqosahResource\Pages;

use App\Filament\Resources\MateriMunaqosahResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMateriMunaqosah extends ViewRecord
{
    protected static string $resource = MateriMunaqosahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

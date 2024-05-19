<?php

namespace App\Filament\Resources\MateriMunaqosahResource\Pages;

use App\Filament\Resources\MateriMunaqosahResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMateriMunaqosahs extends ListRecords
{
    protected static string $resource = MateriMunaqosahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

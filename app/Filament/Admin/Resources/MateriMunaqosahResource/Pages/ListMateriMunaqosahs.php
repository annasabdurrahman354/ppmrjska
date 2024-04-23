<?php

namespace App\Filament\Admin\Resources\MateriMunaqosahResource\Pages;

use App\Filament\Admin\Resources\MateriMunaqosahResource;
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

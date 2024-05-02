<?php

namespace App\Filament\PPSB\Resources\CalonSantriResource\Pages;

use App\Filament\PPSB\Resources\CalonSantriResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCalonSantri extends ListRecords
{
    protected static string $resource = CalonSantriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

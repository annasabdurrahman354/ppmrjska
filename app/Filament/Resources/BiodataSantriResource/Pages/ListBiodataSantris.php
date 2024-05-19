<?php

namespace App\Filament\Resources\BiodataSantriResource\Pages;

use App\Filament\Resources\BiodataSantriResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBiodataSantris extends ListRecords
{
    protected static string $resource = BiodataSantriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

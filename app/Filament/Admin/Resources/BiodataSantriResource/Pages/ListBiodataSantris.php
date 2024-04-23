<?php

namespace App\Filament\Admin\Resources\BiodataSantriResource\Pages;

use App\Filament\Admin\Resources\BiodataSantriResource;
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

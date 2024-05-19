<?php

namespace App\Filament\Resources\PenilaianCalonSantriResource\Pages;

use App\Filament\Resources\PenilaianCalonSantriResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenilaianCalonSantri extends ListRecords
{
    protected static string $resource = PenilaianCalonSantriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

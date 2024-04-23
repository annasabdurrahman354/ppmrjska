<?php

namespace App\Filament\Admin\Resources\DewanGuruResource\Pages;

use App\Filament\Admin\Resources\DewanGuruResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDewanGurus extends ListRecords
{
    protected static string $resource = DewanGuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

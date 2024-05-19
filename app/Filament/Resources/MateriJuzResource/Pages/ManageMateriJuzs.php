<?php

namespace App\Filament\Resources\MateriJuzResource\Pages;

use App\Filament\Resources\MateriJuzResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMateriJuzs extends ManageRecords
{
    protected static string $resource = MateriJuzResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

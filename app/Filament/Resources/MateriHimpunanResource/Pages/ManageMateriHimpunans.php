<?php

namespace App\Filament\Resources\MateriHimpunanResource\Pages;

use App\Filament\Resources\MateriHimpunanResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMateriHimpunans extends ManageRecords
{
    protected static string $resource = MateriHimpunanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\MateriHafalanResource\Pages;

use App\Filament\Resources\MateriHafalanResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMateriHafalan extends ManageRecords
{
    protected static string $resource = MateriHafalanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

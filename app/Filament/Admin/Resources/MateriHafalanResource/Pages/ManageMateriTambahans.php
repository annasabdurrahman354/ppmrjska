<?php

namespace App\Filament\Admin\Resources\MateriHafalanResource\Pages;

use App\Filament\Admin\Resources\MateriHafalanResource;
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

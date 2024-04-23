<?php

namespace App\Filament\Admin\Resources\MateriSuratResource\Pages;

use App\Filament\Admin\Resources\MateriSuratResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMateriSurats extends ManageRecords
{
    protected static string $resource = MateriSuratResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

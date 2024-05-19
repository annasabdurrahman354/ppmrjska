<?php

namespace App\Filament\Resources\MateriTambahanResource\Pages;

use App\Filament\Resources\MateriTambahanResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMateriTambahans extends ManageRecords
{
    protected static string $resource = MateriTambahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

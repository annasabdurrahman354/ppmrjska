<?php

namespace App\Filament\Admin\Resources\KotaResource\Pages;

use App\Filament\Admin\Resources\KotaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKotas extends ListRecords
{
    protected static string $resource = KotaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

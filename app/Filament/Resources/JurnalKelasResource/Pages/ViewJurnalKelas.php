<?php

namespace App\Filament\Resources\JurnalKelasResource\Pages;

use App\Filament\Resources\JurnalKelasResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewJurnalKelas extends ViewRecord
{
    protected static string $resource = JurnalKelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

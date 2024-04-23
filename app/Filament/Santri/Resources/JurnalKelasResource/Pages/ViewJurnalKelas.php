<?php

namespace App\Filament\Santri\Resources\JurnalKelasResource\Pages;

use App\Filament\Santri\Resources\JurnalKelasResource;
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

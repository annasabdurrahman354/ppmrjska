<?php

namespace App\Filament\Resources\DewanGuruResource\Pages;

use App\Filament\Resources\DewanGuruResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDewanGuru extends ViewRecord
{
    protected static string $resource = DewanGuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

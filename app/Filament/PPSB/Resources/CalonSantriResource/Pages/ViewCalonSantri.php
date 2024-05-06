<?php

namespace App\Filament\PPSB\Resources\CalonSantriResource\Pages;

use App\Filament\PPSB\Resources\CalonSantriResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCalonSantri extends ViewRecord
{
    protected static string $resource = CalonSantriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->visible(isSuperAdmin()),
        ];
    }
}

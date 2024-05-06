<?php

namespace App\Filament\PPSB\Resources\PendaftaranResource\Pages;

use App\Filament\PPSB\Resources\PendaftaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPendaftaran extends ViewRecord
{
    protected static string $resource = PendaftaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Admin\Resources\KurikulumResource\Pages;

use App\Filament\Admin\Resources\KurikulumResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKurikulum extends ViewRecord
{
    protected static string $resource = KurikulumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

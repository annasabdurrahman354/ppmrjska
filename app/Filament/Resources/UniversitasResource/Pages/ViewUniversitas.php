<?php

namespace App\Filament\Resources\UniversitasResource\Pages;

use App\Filament\Resources\UniversitasResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUniversitas extends ViewRecord
{
    protected static string $resource = UniversitasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

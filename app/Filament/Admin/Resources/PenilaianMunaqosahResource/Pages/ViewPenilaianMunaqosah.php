<?php

namespace App\Filament\Admin\Resources\PenilaianMunaqosahResource\Pages;

use App\Filament\Admin\Resources\PenilaianMunaqosahResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPenilaianMunaqosah extends ViewRecord
{
    protected static string $resource = PenilaianMunaqosahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

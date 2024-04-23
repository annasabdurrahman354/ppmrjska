<?php

namespace App\Filament\Admin\Resources\PenilaianMunaqosahResource\Pages;

use App\Filament\Admin\Resources\PenilaianMunaqosahResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenilaianMunaqosahs extends ListRecords
{
    protected static string $resource = PenilaianMunaqosahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

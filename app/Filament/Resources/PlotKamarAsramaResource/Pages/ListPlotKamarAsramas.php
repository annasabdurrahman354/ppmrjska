<?php

namespace App\Filament\Resources\PlotKamarAsramaResource\Pages;

use App\Filament\Resources\PlotKamarAsramaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlotKamarAsramas extends ListRecords
{
    protected static string $resource = PlotKamarAsramaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

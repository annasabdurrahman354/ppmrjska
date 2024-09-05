<?php

namespace App\Filament\Resources\AngkatanPondokResource\Pages;

use App\Filament\Resources\AngkatanPondokResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAngkatanPondoks extends ManageRecords
{
    protected static string $resource = AngkatanPondokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

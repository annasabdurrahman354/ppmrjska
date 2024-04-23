<?php

namespace App\Filament\Admin\Resources\MateriHimpunanResource\Pages;

use App\Filament\Admin\Resources\MateriHimpunanResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMateriHimpunans extends ManageRecords
{
    protected static string $resource = MateriHimpunanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

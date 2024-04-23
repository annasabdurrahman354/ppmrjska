<?php

namespace App\Filament\Admin\Resources\KotaResource\Pages;

use App\Filament\Admin\Resources\KotaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKota extends EditRecord
{
    protected static string $resource = KotaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

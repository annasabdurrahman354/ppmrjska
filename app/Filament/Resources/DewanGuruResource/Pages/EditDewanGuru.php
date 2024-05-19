<?php

namespace App\Filament\Resources\DewanGuruResource\Pages;

use App\Filament\Resources\DewanGuruResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDewanGuru extends EditRecord
{
    protected static string $resource = DewanGuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}

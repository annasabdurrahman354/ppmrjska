<?php

namespace App\Filament\Resources\PenilaianMunaqosahResource\Pages;

use App\Filament\Resources\PenilaianMunaqosahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenilaianMunaqosah extends EditRecord
{
    protected static string $resource = PenilaianMunaqosahResource::class;

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

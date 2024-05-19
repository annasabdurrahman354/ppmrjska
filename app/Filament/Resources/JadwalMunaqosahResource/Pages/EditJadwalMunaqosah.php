<?php

namespace App\Filament\Resources\JadwalMunaqosahResource\Pages;

use App\Filament\Resources\JadwalMunaqosahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJadwalMunaqosah extends EditRecord
{
    protected static string $resource = JadwalMunaqosahResource::class;

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

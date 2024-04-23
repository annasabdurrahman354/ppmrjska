<?php

namespace App\Filament\Admin\Resources\PenilaianMunaqosahResource\Pages;

use App\Filament\Admin\Resources\PenilaianMunaqosahResource;
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

<?php

namespace App\Filament\PPSB\Resources\PenilaianCalonSantriResource\Pages;

use App\Filament\PPSB\Resources\PenilaianCalonSantriResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenilaianCalonSantri extends EditRecord
{
    protected static string $resource = PenilaianCalonSantriResource::class;

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

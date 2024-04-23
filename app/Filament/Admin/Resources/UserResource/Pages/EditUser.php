<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
    
    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (isset($data['kelas'])){
            if ($data['kelas']) {
                $data['is_takmili'] = 1;
            } else {
                $data['kelas'] = (string) $data['angkatan_pondok'];
            }
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['is_takmili'])){
            if ($data['is_takmili']) {
                $data['kelas'] = 'takmili';
            } else {
                $data['kelas'] = (string) $data['angkatan_pondok'];
            }
        }
        
        return $data;
    }
}

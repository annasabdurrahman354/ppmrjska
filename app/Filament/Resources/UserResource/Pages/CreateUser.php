<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
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

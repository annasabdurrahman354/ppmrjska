<?php

namespace App\Filament\Resources\AdministrasiResource\Pages;

use App\Filament\Resources\AdministrasiResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAdministrasi extends CreateRecord
{
    protected static string $resource = AdministrasiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['tahun_ajaran'] = $data['tahun_ajaran_awal'].'/'.$data['tahun_ajaran_akhir'];

        return $data;
    }
}

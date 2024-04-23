<?php

namespace App\Filament\Admin\Resources\AdministrasiResource\Pages;

use App\Filament\Admin\Resources\AdministrasiResource;
use Filament\Actions;
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

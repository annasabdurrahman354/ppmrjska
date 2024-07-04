<?php

namespace App\Filament\Resources\AdministrasiResource\Pages;

use App\Enums\JenisTagihan;
use App\Filament\Resources\AdministrasiResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAdministrasi extends CreateRecord
{
    protected static string $resource = AdministrasiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['tahun_ajaran'] = $data['tahun_ajaran_awal'].'/'.$data['tahun_ajaran_akhir'];

        if ($data['jenis_tagihan'] === JenisTagihan::TAHUNAN->value || $data['jenis_tagihan'] === JenisTagihan::SEKALI->value){
            $data['periode_tagihan'] = $data['tahun_ajaran'];
        }
        return $data;
    }
}

<?php

namespace App\Filament\Admin\Resources\KurikulumResource\Pages;

use App\Filament\Admin\Resources\KurikulumResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKurikulum extends CreateRecord
{
    protected static string $resource = KurikulumResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
{
        $semester = 1;
        $temp = [];
        $tempKurikulum = [];
        $temp['angkatan_pondok'] = $data['angkatan_pondok'];
        foreach ($data['plot_kurikulum'] as $kurikulum) {
            $kurikulum['semester'] = $semester;
            $tempKurikulum[] = $kurikulum;
            $semester++;
        }
        $temp['plot_kurikulum'] = $tempKurikulum;
        return $temp;
    }
}

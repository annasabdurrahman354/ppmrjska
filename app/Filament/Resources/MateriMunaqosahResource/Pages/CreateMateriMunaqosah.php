<?php

namespace App\Filament\Resources\MateriMunaqosahResource\Pages;

use App\Filament\Resources\MateriMunaqosahResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMateriMunaqosah extends CreateRecord
{
    protected static string $resource = MateriMunaqosahResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if(isset($data['indikator_hafalan'])){
            $indikatorHafalan = $data['indikator_hafalan'];
            $indikatorHafalan = array_map(function ($string) {
                $words = explode(' ', $string);
                $words = array_map('ucfirst', $words);
                return implode(' ', $words);
            }, $indikatorHafalan);
            $data['indikator_hafalan'] = $indikatorHafalan;
        }

        if(isset($data['indikator_materi'])){
            $indikatorMateri = $data['indikator_materi'];
            $indikatorMateri = array_map(function ($string) {
                $words = explode(' ', $string);
                $words = array_map('ucfirst', $words);
                return implode(' ', $words);
            }, $indikatorMateri);
            $data['indikator_materi'] = $indikatorMateri;
        }

        return $data;
    }
}

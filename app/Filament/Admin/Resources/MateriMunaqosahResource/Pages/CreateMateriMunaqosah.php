<?php

namespace App\Filament\Admin\Resources\MateriMunaqosahResource\Pages;

use App\Filament\Admin\Resources\MateriMunaqosahResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMateriMunaqosah extends CreateRecord
{
    protected static string $resource = MateriMunaqosahResource::class;

    function toTitleCase($string) {
        // Convert the first letter of each word to uppercase.
        $words = explode(' ', $string);
        $words = array_map('ucfirst', $words);
      
        // Return the title-cased string.
        return implode(' ', $words);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['tahun_ajaran'] = $data['tahun_ajaran_awal'].'/'.$data['tahun_ajaran_akhir'];
        
        function toTitleCase($string) {
            $words = explode(' ', $string);
            $words = array_map('ucfirst', $words);
            return implode(' ', $words);
        }
          
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

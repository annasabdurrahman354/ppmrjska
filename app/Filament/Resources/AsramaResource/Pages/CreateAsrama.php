<?php

namespace App\Filament\Resources\AsramaResource\Pages;

use App\Filament\Resources\AsramaResource;
use App\Models\KamarAsrama;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAsrama extends CreateRecord
{
    protected static string $resource = AsramaResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Extract lantai and kamar_asrama data before saving the asrama
        $lantai = $data['lantai'] ?? [];
        unset($data['lantai']);

        $this->record = static::getModel()::create($data);

        foreach ($lantai as $lantaiData) {
            $nomor_lantai = $lantaiData['nomor_lantai'];
            $kamar_asrama = $lantaiData['kamar_asrama'] ?? [];

            foreach ($kamar_asrama as $kamarData) {
                KamarAsrama::create([
                    'asrama_id' => $this->record->id,
                    'lantai' => $nomor_lantai,
                    'nomor_kamar' => $kamarData['nomor_kamar'],
                    'status_ketersediaan' => $kamarData['status_ketersediaan'],
                ]);
            }
        }

        return $this->record;
    }
}

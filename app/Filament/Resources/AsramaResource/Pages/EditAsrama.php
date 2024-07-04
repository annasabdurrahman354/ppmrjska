<?php

namespace App\Filament\Resources\AsramaResource\Pages;

use App\Filament\Resources\AsramaResource;
use App\Models\KamarAsrama;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditAsrama extends EditRecord
{
    protected static string $resource = AsramaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Extract lantai and kamar_asrama data before updating the asrama
        $lantai = $data['lantai'] ?? [];
        unset($data['lantai']);

        $updatedKamarIds = [];

        foreach ($lantai as $lantaiData) {
            $nomor_lantai = $lantaiData['nomor_lantai'];
            $kamar_asrama = $lantaiData['kamar_asrama'] ?? [];

            foreach ($kamar_asrama as $kamarData) {
                if (isset($kamarData['id'])) {
                    // Update existing kamar_asrama
                    $kamar = KamarAsrama::find($kamarData['id']);
                    if ($kamar) {
                        $kamar->update([
                            'lantai' => $nomor_lantai,
                            'nomor_kamar' => $kamarData['nomor_kamar'],
                            'status_ketersediaan' => $kamarData['status_ketersediaan'],
                        ]);
                        $updatedKamarIds[] = $kamar->id;
                    }
                } else {
                    // Create new kamar_asrama
                    $kamar = KamarAsrama::create([
                        'asrama_id' => $this->record->id,
                        'lantai' => $nomor_lantai,
                        'nomor_kamar' => $kamarData['nomor_kamar'],
                        'status_ketersediaan' => $kamarData['status_ketersediaan'],
                    ]);
                    $updatedKamarIds[] = $kamar->id;
                }
            }
        }

        // Delete kamar_asrama that are not in the updated list
        KamarAsrama::where('asrama_id', $this->record->id)
            ->whereNotIn('id', $updatedKamarIds)
            ->delete();

        $record->update($data);

        return $record;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $lantaiData = [];

        // Group kamar_asrama by lantai
        $kamarAsramaGrouped = KamarAsrama::where('asrama_id', $this->record->id)
            ->get()
            ->groupBy('lantai');

        foreach ($kamarAsramaGrouped as $lantai => $kamarAsrama) {
            $lantaiData[] = [
                'nomor_lantai' => $lantai,
                'kamar_asrama' => $kamarAsrama->map(function ($kamar) {
                    return [
                        'nomor_kamar' => $kamar->nomor_kamar,
                        'status_ketersediaan' => $kamar->status_ketersediaan,
                    ];
                })->toArray(),
            ];
        }

        $data['lantai'] = $lantaiData;

        return $data;
    }
}

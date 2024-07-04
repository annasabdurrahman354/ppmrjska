<?php

namespace App\Filament\Resources\AsramaResource\Pages;

use App\Filament\Resources\AsramaResource;
use App\Models\KamarAsrama;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAsrama extends ViewRecord
{
    protected static string $resource = AsramaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
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

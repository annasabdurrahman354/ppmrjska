<?php

namespace App\Filament\Resources\PlotKamarAsramaResource\Pages;

use App\Filament\Resources\PlotKamarAsramaResource;
use App\Models\Asrama;
use App\Models\PlotKamarAsrama;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPlotKamarAsrama extends ViewRecord
{
    protected static string $resource = PlotKamarAsramaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Example: Manipulate data before filling the form
        if (isset($data['tahun_ajaran'])) {
            list($tahun_awal, $tahun_akhir) = explode('/', $data['tahun_ajaran']);
            $data['tahun_ajaran_awal'] = $tahun_awal;
            $data['tahun_ajaran_akhir'] = $tahun_akhir;
        }

        // Retrieve asrama data from the database
        $asramaData = Asrama::with(['kamarAsrama'])->get();

        $data['asrama'] = $asramaData->map(function ($asrama) use ($data) {
            return [
                'asrama_id' => $asrama->id,
                'penghuni' => $asrama->penghuni,
                'lantai' => $asrama->kamarAsrama->groupBy('lantai')->map(function ($kamarPerLantai, $lantai) use ($data) {
                    return [
                        'nomor_lantai' => $lantai,
                        'kamar_asrama' => $kamarPerLantai->map(function ($kamar) use ($data) {
                            $penghuni = PlotKamarAsrama::where('kamar_asrama_id', $kamar->id)
                                ->where('tahun_ajaran', $data['tahun_ajaran'])
                                ->pluck('user_id')
                                ->toArray();

                            return [
                                'kamar_asrama_id' => $kamar->id,
                                'penghuni' => array_map(function ($userId) {
                                    return ['user_id' => $userId];
                                }, $penghuni),
                            ];
                        })->toArray(),
                    ];
                })->values()->toArray(),
            ];
        })->toArray();

        return $data;
    }
}

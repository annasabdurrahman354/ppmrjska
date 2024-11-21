<?php

namespace App\Filament\Resources\PlotKamarAsramaResource\Pages;

use App\Filament\Resources\PlotKamarAsramaResource;
use App\Models\Asrama;
use App\Models\PlotKamarAsrama;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPlotKamarAsrama extends EditRecord
{
    protected static string $resource = PlotKamarAsramaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
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

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Delete all existing PlotKamarAsrama records for the given tahun_ajaran
        PlotKamarAsrama::where('tahun_ajaran', $data['tahun_ajaran'])->delete();

        // Prepare new PlotKamarAsrama records for bulk insertion
        $newPlots = [];
        foreach ($data['asrama'] as $asramaData) {
            foreach ($asramaData['lantai'] as $lantaiData) {
                foreach ($lantaiData['kamar_asrama'] as $kamarData) {
                    foreach ($kamarData['penghuni'] as $penghuniData) {
                        $newPlots[] = [
                            'tahun_ajaran' => $data['tahun_ajaran'],
                            'kamar_asrama_id' => $kamarData['kamar_asrama_id'],
                            'user_id' => $penghuniData['user_id'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
        }

        // Bulk insert new PlotKamarAsrama records
        if (!empty($newPlots)) {
            PlotKamarAsrama::insert($newPlots);
        }

        return $record;
    }
}

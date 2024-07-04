<?php

namespace App\Filament\Resources\PlotKamarAsramaResource\Pages;

use App\Filament\Resources\PlotKamarAsramaResource;
use App\Models\PlotKamarAsrama;
use App\Models\TahunAjaran;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePlotKamarAsrama extends CreateRecord
{
    protected static string $resource = PlotKamarAsramaResource::class;
    protected function handleRecordCreation(array $data): Model
    {
        // Create or update TahunAjaran
        $tahunAjaran = TahunAjaran::where('tahun_ajaran', $data['tahun_ajaran_awal'].'/'.$data['tahun_ajaran_akhir'])->first();
        if ($tahunAjaran) {
            Notification::make()
                ->title('Plotingan untuk tahun ajaran '. $tahunAjaran->tahun_ajaran . 'telah ada!')
                ->body('Klik untuk edit.')
                ->warning()
                ->persistent()
                ->actions([
                    Action::make('edit')
                        ->button()
                        ->url(PlotKamarAsramaResource::getUrl('edit', ['record' => $tahunAjaran]), shouldOpenInNewTab: true),
                ])
                ->send();
            $this->halt();
        }
        $tahunAjaran = TahunAjaran::create(['tahun_ajaran' => $data['tahun_ajaran_awal'].'/'.$data['tahun_ajaran_akhir']]);

        // Handle Asrama and related KamarAsrama and PlotKamarAsrama
        foreach ($data['asrama'] as $asramaData) {
            foreach ($asramaData['lantai'] as $lantaiData) {
                foreach ($lantaiData['kamar_asrama'] as $kamarData) {
                    foreach ($kamarData['penghuni'] as $penghuniData) {
                        PlotKamarAsrama::create([
                            'tahun_ajaran' => $tahunAjaran->tahun_ajaran,
                            'kamar_asrama_id' => $kamarData['kamar_asrama_id'],
                            'user_id' => $penghuniData['user_id'],
                        ]);
                    }
                }
            }
        }

        return $tahunAjaran;
    }
}

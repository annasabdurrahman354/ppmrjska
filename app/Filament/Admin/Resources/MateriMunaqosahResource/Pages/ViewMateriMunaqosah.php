<?php

namespace App\Filament\Admin\Resources\MateriMunaqosahResource\Pages;

use App\Filament\Admin\Resources\MateriMunaqosahResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMateriMunaqosah extends ViewRecord
{
    protected static string $resource = MateriMunaqosahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $split_values = explode("/", $data['tahun_ajaran']);

        // Assign the first element of the array to the tahun_ajaran_awal variable and the second element of the array to the tahun_ajaran_akhir variable.
        $tahun_ajaran_awal = $split_values[0];
        $tahun_ajaran_akhir = $split_values[1];

        // Add the tahun_ajaran_awal and tahun_ajaran_akhir variables to the new array variable.
        $data["tahun_ajaran_awal"] = $tahun_ajaran_awal;
        $data["tahun_ajaran_akhir"] = $tahun_ajaran_akhir;
        
        return $data;
    }
}

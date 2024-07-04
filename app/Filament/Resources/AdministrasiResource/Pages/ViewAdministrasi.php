<?php

namespace App\Filament\Resources\AdministrasiResource\Pages;

use App\Filament\Resources\AdministrasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAdministrasi extends ViewRecord
{
    protected static string $resource = AdministrasiResource::class;

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

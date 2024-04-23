<?php

namespace App\Filament\Resources\Admin\JurnalKelasResource\Pages;

use App\Filament\Resources\Admin\JurnalKelasResource;
use App\Models\MateriHimpunan;
use App\Models\MateriSurat;
use App\Models\MateriTambahan;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListJurnalKelas extends ListRecords
{
    protected static string $resource = JurnalKelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            null => Tab::make('All'),
            'Al-Quran' => Tab::make()->query(fn ($query) => $query->where('materi_awal_type', '=', MateriSurat::class)->orWhere('materi_akhir_type', '=', MateriSurat::class)),
            'Himpunan' => Tab::make()->query(fn ($query) => $query->where('materi_awal_type', '=', MateriHimpunan::class)->orWhere('materi_akhir_type', '=', MateriHimpunan::class)),
            'Lain-lain' => Tab::make()->query(fn ($query) => $query->where('materi_awal_type', '=', MateriTambahan::class)->orWhere('materi_akhir_type', '=', MateriTambahan::class)),
        ];
        return $tabs;
    }
}

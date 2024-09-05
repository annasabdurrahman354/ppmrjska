<?php

namespace App\Filament\Resources\JadwalMunaqosahResource\Pages;

use App\Enums\StatusPondok;
use App\Filament\Resources\JadwalMunaqosahResource;
use App\Models\User;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\Js;

class ListJadwalMunaqosahs extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = JadwalMunaqosahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return JadwalMunaqosahResource::getWidgets();
    }

    #[Js]
    public function getTabs(): array
    {
        $semuaKelas =  User::whereNotIn('status_pondok', [StatusPondok::KELUAR, StatusPondok::LULUS])
            ->join('angkatan_pondok', 'users.angkatan_pondok', '=', 'angkatan_pondok.angkatan_pondok')
            ->distinct()
            ->orderBy('angkatan_pondok.kelas')
            ->pluck('angkatan_pondok.kelas');

        $tabs = [
            null => Tab::make('All'),
        ];

        foreach ($semuaKelas as $kelas){
            $tabs[$kelas] = Tab::make()->query(fn ($query) => $query->whereHas('materiMunaqosah', function ($query) use ($kelas) {
                $query->where('kelas', $kelas);
            }));
        }

        $this->js(<<<JS
            const tabsItems = document.querySelectorAll('.fi-tabs-item');
            tabsItems.forEach(item => {
                item.addEventListener('click', function() {
                    let urlParams = new URLSearchParams(window.location.search);
                    let tab = urlParams.get('activeTab');

                });
            });
        JS);

        return $tabs;
    }
}

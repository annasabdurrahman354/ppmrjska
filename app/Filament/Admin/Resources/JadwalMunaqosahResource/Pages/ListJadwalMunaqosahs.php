<?php

namespace App\Filament\Admin\Resources\JadwalMunaqosahResource\Pages;

use App\Enums\StatusPondok;
use App\Filament\Admin\Resources\JadwalMunaqosahResource;
use App\Models\User;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
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
        $semuaKelas = User::where('tanggal_lulus_pondok', null)
            ->where('status_pondok', StatusPondok::AKTIF->value)
            ->orderBy('kelas')
            ->select('kelas')
            ->distinct()
            ->get()
            ->pluck('kelas');

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

<?php

namespace App\Filament\Resources\JadwalMunaqosahResource\Pages;

use App\Enums\StatusPondok;
use App\Filament\Resources\JadwalMunaqosahResource;
use App\Models\MateriMunaqosah;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
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
            Actions\Action::make('cetakJadwalMunaqosah')
                ->label('Cetak Jadwal')
                ->form([
                    Fieldset::make('Rentang Jadwal')
                        ->label('Rentang Jadwal')
                        ->schema([
                            DatePicker::make('tanggal_awal')
                                ->label('Tanggal Awal')
                                ->required(),
                            DatePicker::make('tanggal_akhir')
                                ->label('Tanggal Akhir')
                                ->required(),
                        ])
                ])
                ->action(function (array $data){
                    $materi = MateriMunaqosah::whereHas('jadwalMunaqosah', function($q) use($data) {
                        $q->whereBetween('waktu', [$data['tanggal_awal'], $data['tanggal_akhir']]);
                    })
                        ->with(['jadwalMunaqosah' => function ($query)  use($data) {
                            $query->whereBetween('waktu', [$data['tanggal_awal'], $data['tanggal_akhir']]);
                        }])
                        ->get()->groupBy('angkatan_pondok');
                    $pdf = Pdf::loadview('exports.plot-jadwal-munaqosah', ['array' => $materi])->setPaper('a4', 'landscape');
                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->stream();
                    }, 'jadwal_munaqosah.pdf');
                })
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

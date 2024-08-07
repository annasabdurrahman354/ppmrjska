<?php

namespace App\Filament\Pages\KetercapaianMateri;

use App\Models\JurnalKelas;
use App\Models\MateriHafalan;
use App\Models\MateriHimpunan;
use App\Models\MateriJuz;
use App\Models\MateriSurat;
use App\Models\MateriTambahan;
use App\Models\PlotKurikulum;
use App\Models\PlotKurikulumMateri;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\IconPosition;

class KetercapaianMateri extends Page implements HasActions
{
    use InteractsWithActions;
    protected static ?string $slug = 'ketercapaian-materi';
    protected static ?string $navigationLabel = 'Ketercapaian Materi';
    protected static ?string $navigationGroup = 'Manajemen Kelas';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?int $navigationSort = 53;

    protected static string $view = 'filament.pages.ketercapaian-materi';

    public ?array $data = [];
    public ?array $ketercapaianMateri = [];

    public function loadKetercapaianMateri(): void
    {
        $angkatan_pondok = $this->form->getState()['angkatan_pondok'];
        $plotKurikulumBySemester = PlotKurikulum::with('plotKurikulumMateri')
            ->whereHas('kurikulum', function ($query) use ($angkatan_pondok) {
                $query->where('angkatan_pondok', $angkatan_pondok);
            })
            ->get()
            ->groupBy('semester')
            ->map(function ($plots, $semester) use ($angkatan_pondok) {
                $results = [];
                $plotKurikulumMateri = $plots->flatMap->plotKurikulumMateri->toArray();
                $allHalamanQuranTercapai = [];

                $jurnalKelasSuratRecords = JurnalKelas::where('materi_awal_type', MateriSurat::class)
                    ->where('materi_akhir_type', MateriSurat::class)
                    ->whereHas('presensiKelas', function ($query) {
                        $query->where('user_id', auth()->user()->id);
                    })
                    ->get();

                foreach ($jurnalKelasSuratRecords as $jurnalKelas) {
                    // Get halaman_awal and halaman_akhir
                    $halamanAwal = $jurnalKelas->halaman_awal;
                    $halamanAkhir = $jurnalKelas->halaman_akhir;

                    // Create an array of pages for this record
                    $halamanTercapai = range($halamanAwal, $halamanAkhir);

                    // Merge with the overall array of pages
                    $allHalamanQuranTercapai = array_merge($allHalamanQuranTercapai, $halamanTercapai);
                }

                $allHalamanQuranTercapai = array_unique($allHalamanQuranTercapai);
                sort($allHalamanQuranTercapai);

                foreach ($plotKurikulumMateri as $materiData) {
                    $materi = $materiData['materi_type']::where('id', $materiData['materi_id'])->first();
                    $ketercapaianMateri = KetercapaianMateri::where('materi_type', $materiData['materi_type'])
                        ->where('materi_id', $materiData['materi_id'])
                        ->first();

                    if (!$materi) break;
                    if ($materiData['materi_type'] === MateriHafalan::class) {
                        $results[] = [
                            'plotKurikulumMateriId' => $materiData['id'],
                            'materi_id' => $materi->id,
                            'materi_type' => $materiData['materi_type'],
                            'nama_materi' => $materi->nama,
                            'status_tercapai' => $materiData['status_tercapai'],
                        ];
                    }
                    else {
                        $halamanAwal = $materi->halaman_awal;
                        $halamanAkhir = $materi->halaman_akhir;
                        $totalHalaman = $materi->halaman_akhir - $materi->halaman_awal + 1;
                        $results[] = [
                            'plotKurikulumMateriId' => $materiData['id'],
                            'materi_id' => $materi->id,
                            'materi_type' => $materiData['materi_type'],
                            'nama_materi' => $materi->nama,
                            'halaman_awal' => $materi->halaman_awal,
                            'halaman_akhir' => $materi->halaman_akhir,
                            'total_halaman' => $totalHalaman,
                            'nonprediksi' => fn ($ketercapaianMateri) => $ketercapaianMateri ? [
                                'jumlah_halaman_tercapai' => $ketercapaianMateri->jumlahHalamanTercapai,
                                'persen_tercapai' => $totalHalaman > 0 ? (int)(($ketercapaianMateri->jumlahHalamanTercapai * 100) / $totalHalaman) : 0,
                                'status_tercapai' => $ketercapaianMateri->jumlahHalamanTercapai >= $totalHalaman,
                                'terkahir_diperbarui' => $ketercapaianMateri->updated_at,
                                'prediksi' => false
                            ] : null,
                            'prediksi' => function ($materiData, $materi, $halamanAwal, $halamanAkhir, $totalHalaman, $allHalamanQuranTercapai) {
                                if ($materiData['materi_type'] === MateriHimpunan::class || $materiData['materi_type'] === MateriTambahan::class){
                                    $jurnalKelasData = JurnalKelas::where('materi_awal_type', $materiData['materi_type'])
                                        ->where('materi_awal_id', $materiData['materi_id'])
                                        ->whereHas('presensiKelas', function ($query) {
                                            $query->where('user_id', auth()->user()->id);
                                        })
                                        ->get()
                                        ->map(function ($jurnal) use ($materi) {
                                            return [
                                                'id' => $jurnal->id,
                                                'tanggal' => $jurnal->tanggal,
                                                'halaman_awal' => $jurnal->halaman_awal,
                                                'halaman_akhir' => function () use ($materi, $jurnal) {
                                                    if ($jurnal->materi_akhir_type == $jurnal->materi_awal_type && $jurnal->materi_akhir_id == $jurnal->materi_awal_id) {
                                                        return $jurnal->halaman_akhir;
                                                    }
                                                    else{
                                                        return $materi->halaman_akhir;
                                                    }
                                                },
                                            ];
                                        });

                                    $jumlahHalamanTercapai = $jurnalKelasData->sum(function ($jurnal) {
                                        return $jurnal['halaman_akhir'] - $jurnal['halaman_awal'] + 1;
                                    });

                                    return [
                                        'jumlah_halaman_tercapai' => $jumlahHalamanTercapai,
                                        'persen_tercapai' => $totalHalaman > 0 ? (int)(($jumlahHalamanTercapai * 100) / $totalHalaman) : 0,
                                        'status_tercapai' => $materiData['status_tercapai'] || ($jumlahHalamanTercapai >= $totalHalaman),
                                        'terakhir_diperbarui' => null,
                                        'prediksi' => true
                                    ];
                                }
                                else {
                                    $jumlahHalamanTercapai = count(array_filter($allHalamanQuranTercapai, function ($halaman) use ($halamanAwal, $halamanAkhir) {
                                        return $halaman >= $halamanAwal && $halaman <= $halamanAkhir;
                                    }));
                                    return [
                                        'jumlah_halaman_tercapai' => $jumlahHalamanTercapai,
                                        'persen_tercapai' => $totalHalaman > 0 ? (int)(($jumlahHalamanTercapai * 100) / $totalHalaman) : 0,
                                        'status_tercapai' => $materiData['status_tercapai'] || ($jumlahHalamanTercapai >= $totalHalaman),
                                        'terakhir_diperbarui' => null,
                                        'prediksi' => true
                                    ];
                                }
                            }
                        ];
                    }
                }
                return [
                    'semester' => $semester,
                    'materi' => $results,
                ];
            })
            ->values();
        $this->ketercapaianMateri = $plotKurikulumBySemester->toArray();
    }

    public function tuntaskanMateriAction(): Action
    {
        return Action::make('tuntaskanMateri')
            ->label('Tuntaskan')
            ->requiresConfirmation()
            ->icon('heroicon-m-sparkles')
            ->iconPosition(IconPosition::After)
            ->size(ActionSize::ExtraSmall)
            ->color('success')
            ->action(function (array $arguments) {
                PlotKurikulumMateri::find($arguments['plotKurikulumMateriId'])->update([
                    'status_tercapai' => true,
                ]);
                $this->loadKetercapaianMateri();
            });
    }

    public function belumTuntaskanMateriAction(): Action
    {
        return Action::make('belumTuntaskanMateri')
            ->label('Reset')
            ->requiresConfirmation()
            ->icon('heroicon-m-exclamation-triangle')
            ->iconPosition(IconPosition::After)
            ->size(ActionSize::ExtraSmall)
            ->color('danger')
            ->action(function (array $arguments) {
                PlotKurikulumMateri::find($arguments['plotKurikulumMateriId'])->update([
                    'status_tercapai' => false,
                ]);
                $this->loadKetercapaianMateri();
            });
    }
    public function exportKetercapaianMateri()
    {

    }
}

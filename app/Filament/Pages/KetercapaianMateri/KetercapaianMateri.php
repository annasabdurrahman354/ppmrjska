<?php

namespace App\Filament\Pages\KetercapaianMateri;

use App\Models\AngkatanPondok;
use App\Models\JurnalKelas;
use App\Models\MateriHafalan;
use App\Models\MateriHimpunan;
use App\Models\MateriSurat;
use App\Models\MateriTambahan;
use App\Models\PlotKurikulum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Pages\Page;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\IconPosition;

class KetercapaianMateri extends Page implements HasActions
{
    use InteractsWithActions;
    use HasPageShield;

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
        $angkatan_pondok = auth()->user()->angkatan_pondok;
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
                    $ketercapaianMateri = \App\Models\KetercapaianMateri::where('materi_type', $materiData['materi_type'])
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
                            'nonprediksi' => $ketercapaianMateri ? [
                                'persen_tercapai' => $ketercapaianMateri->ketercapaian_materi,
                                'status_tercapai' => $ketercapaianMateri->ketercapaian_materi == 100,
                                'terkahir_diperbarui' => $ketercapaianMateri->updated_at ?? $ketercapaianMateri->created_at,
                                'prediksi' => false
                            ] : null,
                            'prediksi' => ($materiData['materi_type'] === MateriHimpunan::class || $materiData['materi_type'] === MateriTambahan::class) ?
                                [
                                    'jumlah_halaman_tercapai' => $jurnalKelasData = JurnalKelas::where('materi_awal_type', $materiData['materi_type'])
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
                                                'halaman_akhir' => $jurnal->materi_akhir_type == $jurnal->materi_awal_type && $jurnal->materi_akhir_id == $jurnal->materi_awal_id
                                                    ? $jurnal->halaman_akhir
                                                    : $materi->halaman_akhir,
                                            ];
                                        })
                                        ->sum(function ($jurnal) {
                                            return $jurnal['halaman_akhir'] - $jurnal['halaman_awal'] + 1;
                                        }),
                                    'persen_tercapai' => $totalHalaman > 0 ? (int)(($jurnalKelasData * 100) / $totalHalaman) : 0,
                                    'status_tercapai' => $materiData['status_tercapai'] || ($jurnalKelasData >= $totalHalaman),
                                    'terakhir_diperbarui' => null,
                                    'prediksi' => true
                                ] :
                                [
                                    'persen_tercapai' => $totalHalaman > 0 ? (int)(($jumlahHalamanTercapai * 100) / $totalHalaman) : 0,
                                    'status_tercapai' => $materiData['status_tercapai'] || ($jumlahHalamanTercapai >= $totalHalaman),
                                    'terakhir_diperbarui' => null,
                                    'prediksi' => true
                                ]
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

    public function perbaruiKetercapaianMateri(): Action
    {
        return Action::make('perbaruiKetercapaianMateri')
            ->label('Perbarui Progress')
            ->requiresConfirmation()
            ->icon('heroicon-m-sparkles')
            ->iconPosition(IconPosition::After)
            ->size(ActionSize::ExtraSmall)
            ->color('success')
            ->form(\App\Models\KetercapaianMateri::getFormKetercapaianMateriAction())
            ->fillForm(fn (array $arguments): array => [
                'user_id' => auth()->user()->id,
                'materi_type' => $arguments['materi_type'],
                'materi_id' => $arguments['materi_id'],
                'ketercapaian_materi' => $arguments['ketercapaian_materi'],
            ])
            ->action(function (array $data) {
                \App\Models\KetercapaianMateri::updateOrCreate(
                    [
                        'user_id' =>  $data['user_id'],
                        'materi_type' => $data['materi_type'],
                        'materi_id' => $data['materi_id'],
                    ],
                    ['ketercapaian_materi' => $data['ketercapaian_materi']]
                );
                $this->loadKetercapaianMateri();
            });
    }

    public function exportKetercapaianMateri()
    {

    }
}

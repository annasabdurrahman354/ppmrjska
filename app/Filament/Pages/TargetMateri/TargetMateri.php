<?php

namespace App\Filament\Pages\TargetMateri;

use App\Models\AngkatanPondok;
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

class TargetMateri extends Page implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;
    protected static ?string $slug = 'target-materi';
    protected static ?string $navigationLabel = 'Target Materi';
    protected static ?string $navigationGroup = 'Manajemen Kelas';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?int $navigationSort = 53;

    protected static string $view = 'filament.pages.target-materi';

    public ?array $data = [];
    public ?array $targetMateri = [];
    public $merekap = false;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Kurikulum')
                    ->schema([
                        Select::make('angkatan_pondok')
                            ->label('Angkatan Pondok')
                            ->required()
                            ->disabled(cant('view_any_kurikulum'))->dehydrated()
                            ->options(
                                AngkatanPondok::select('angkatan_pondok')
                                    ->orderBy('angkatan_pondok')
                                    ->distinct()
                                    ->get()
                                    ->pluck('angkatan_pondok', 'angkatan_pondok')
                            )
                            ->default(match (auth()->user()->angkatan_pondok) {
                                config('filament-shield.super_admin.name') => 'Takmili',
                                default => auth()->user()->angkatan_pondok
                            }),
                    ])
            ])
            ->statePath('data');
    }

    public function loadTargetMateri(): void
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

                $angkatanPondok = AngkatanPondok::where('angkatan_pondok', $angkatan_pondok)->first();

                $jurnalKelasSuratRecords = JurnalKelas::whereJsonContains('kelas', $angkatan_pondok)
                    ->when($angkatanPondok->kelas === 'Takmili', function ($query) use ($angkatanPondok) {
                        $query->orWhere('tanggal', '>=', $angkatanPondok->tanggal_masuk_takmili)
                            ->whereJsonContains('kelas', 'Takmili');
                    })
                    ->where('materi_awal_type', MateriSurat::class)
                    ->where('materi_akhir_type', MateriSurat::class)
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
                    else if ($materiData['materi_type'] === MateriJuz::class){
                        $halamanAwal = $materi->halaman_awal;
                        $halamanAkhir = $materi->halaman_akhir;

                        $jumlahHalamanTercapai = count(array_filter($allHalamanQuranTercapai, function ($halaman) use ($halamanAwal, $halamanAkhir) {
                            return $halaman >= $halamanAwal && $halaman <= $halamanAkhir;
                        }));

                        $totalHalaman = $materi->halaman_akhir - $materi->halaman_awal + 1;

                        $results[] = [
                            'plotKurikulumMateriId' => $materiData['id'],
                            'materi_id' => $materi->id,
                            'materi_type' => $materiData['materi_type'],
                            'nama_materi' => $materi->nama,
                            'halaman_awal' => $materi->halaman_awal,
                            'halaman_akhir' => $materi->halaman_akhir,
                            'total_halaman' => $totalHalaman,
                            'jumlah_halaman_tercapai' => $jumlahHalamanTercapai,
                            'persen_tercapai' => $totalHalaman > 0 ? (int)(($jumlahHalamanTercapai * 100) / $totalHalaman) : 0,
                            'status_tercapai' => $materiData['status_tercapai'] || ($jumlahHalamanTercapai >= $totalHalaman),
                        ];
                    }
                    else if ($materiData['materi_type'] === MateriHimpunan::class || $materiData['materi_type'] === MateriTambahan::class){
                        $jurnalKelasData = JurnalKelas::whereJsonContains('kelas', $angkatan_pondok)
                            ->when($angkatanPondok->kelas === 'Takmili', function ($query) use ($angkatanPondok) {
                                $query->orWhere('tanggal','>=', $angkatanPondok->tanggal_masuk_takmili)
                                    ->whereJsonContains('kelas', 'Takmili');
                            })
                            ->where('materi_awal_type', $materiData['materi_type'])
                            ->where('materi_awal_id', $materiData['materi_id'])
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

                        $totalHalaman = $materi->halaman_akhir - $materi->halaman_awal + 1;
                        $jumlahHalamanTercapai = $jurnalKelasData->sum(function ($jurnal) {
                            return $jurnal['halaman_akhir'] - $jurnal['halaman_awal'] + 1;
                        });

                        $results[] = [
                            'plotKurikulumMateriId' => $materiData['id'],
                            'materi_id' => $materi->id,
                            'materi_type' => $materiData['materi_type'],
                            'nama_materi' => $materi->nama,
                            'halaman_awal' => $materi->halaman_awal,
                            'halaman_akhir' => $materi->halaman_akhir,
                            'total_halaman' => $totalHalaman,
                            'jumlah_halaman_tercapai' => $jumlahHalamanTercapai,
                            'persen_tercapai' => $totalHalaman > 0 ? (int)(($jumlahHalamanTercapai * 100) / $totalHalaman) : 0,
                            'status_tercapai' => $materiData['status_tercapai'] || ($jumlahHalamanTercapai >= $totalHalaman),
                        ];
                    }
                }
                return [
                    'semester' => $semester,
                    'materi' => $results,
                ];
            })
            ->values();
        $this->targetMateri = $plotKurikulumBySemester->toArray();
        $this->merekap = true;
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
                $this->loadTargetMateri();
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
                $this->loadTargetMateri();
            });
    }
    public function exportTargetMateri()
    {

    }
}

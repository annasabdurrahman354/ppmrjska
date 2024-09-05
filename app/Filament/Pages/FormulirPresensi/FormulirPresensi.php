<?php

namespace App\Filament\Pages\FormulirPresensi;

use App\Enums\JenisKelamin;
use App\Enums\PeriodeTagihanBulanan;
use App\Enums\StatusPondok;
use App\Exports\RekapPresensiExport;
use App\Models\AngkatanPondok;
use App\Models\JurnalKelas;
use App\Models\PresensiKelas;
use App\Models\User;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Maatwebsite\Excel\Facades\Excel;
class FormulirPresensi extends Page implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    protected static ?string $slug = 'formulir-presensi';
    protected static ?string $navigationLabel = 'Formulir Presensi';
    protected static ?string $navigationGroup = 'Manajemen Kelas';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?int $navigationSort = 52;

    protected static string $view = 'filament.pages.formulir-presensi';

    public ?array $data = [];
    public ?array $attendanceData = [];
    public ?array $distinctTanggalSesi = [];

    public ?array $kelas = [];
    public ?string $jenis_kelamin = '';
    public ?string $bulan = '';
    public $merekap = false;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Kelas')
                    ->schema([
                        Select::make('kelas')
                            ->label('Kelas')
                            ->multiple()
                            ->required()
                            ->maxItems(fn () => cant('rekap_kelas_lain_jurnal::kelas') ? 1 : 6)
                            ->disabled(cant('rekap_kelas_lain_jurnal::kelas'))->dehydrated()
                            ->options(
                                AngkatanPondok::select('kelas')
                                    ->orderBy('kelas')
                                    ->distinct()
                                    ->get()
                                    ->pluck('kelas', 'kelas')
                            )
                            ->default(match (auth()->user()->kelas) {
                                config('filament-shield.super_admin.name') => ['Takmili'],
                                default => [auth()->user()->kelas]
                            }),
                        ToggleButtons::make('jenis_kelamin')
                            ->label('Santri')
                            ->inline()
                            ->grouped()
                            ->disabled(cant('rekap_kelas_lain_jurnal::kelas'))->dehydrated()
                            ->options(JenisKelamin::class)
                            ->default(auth()->user()->jenis_kelamin)
                            ->required(),
                        ToggleButtons::make('bulan')
                            ->label('Bulan')
                            ->options(PeriodeTagihanBulanan::class)
                            ->inline()
                            ->required(),
                    ])
            ])
            ->statePath('data');
    }

    public function loadRekapPresensi(): void
    {
        $this->kelas = $this->form->getState()['kelas'];
        $this->jenis_kelamin = $this->form->getState()['jenis_kelamin']->value;
        $this->bulan = $this->form->getState()['bulan'];

        $monthMapping = [
            PeriodeTagihanBulanan::JANUARI->value => 1,
            PeriodeTagihanBulanan::FEBRUARI->value => 2,
            PeriodeTagihanBulanan::MARET->value => 3,
            PeriodeTagihanBulanan::APRIL->value => 4,
            PeriodeTagihanBulanan::MEI->value => 5,
            PeriodeTagihanBulanan::JUNI->value => 6,
            PeriodeTagihanBulanan::JULI->value => 7,
            PeriodeTagihanBulanan::AGUSTUS->value => 8,
            PeriodeTagihanBulanan::SEPTEMBER->value => 9,
            PeriodeTagihanBulanan::OKTOBER->value => 10,
            PeriodeTagihanBulanan::NOVEMBER->value => 11,
            PeriodeTagihanBulanan::DESEMBER->value => 12,
        ];

        // Get the corresponding month number
        $monthNumber = $monthMapping[$this->bulan];

        $distinctTanggalSesi = JurnalKelas::join('presensi_kelas', 'jurnal_kelas.id', '=', 'presensi_kelas.jurnal_kelas_id')
            ->join('users', 'presensi_kelas.user_id', '=', 'users.id')
            ->join('angkatan_pondok', 'users.angkatan_pondok', '=', 'angkatan_pondok.angkatan_pondok') // Specify the correct column from 'angkatan_pondok'
            ->whereIn('angkatan_pondok.kelas', $this->kelas)
            ->where('users.jenis_kelamin', $this->jenis_kelamin)
            ->whereNotIn('users.status_pondok', [StatusPondok::NONAKTIF->value, StatusPondok::LULUS->value, StatusPondok::KELUAR->value])
            ->whereMonth('jurnal_kelas.tanggal', $monthNumber) // Ensure 'tanggal' is from the correct table
            ->distinct()
            ->get(['jurnal_kelas.tanggal', 'jurnal_kelas.sesi'])
            ->mapToGroups(function ($item) {
                return [(string) $item->tanggal => $item->sesi];
            });

        $santris = User::where('jenis_kelamin', $this->jenis_kelamin)
            ->whereKelasIn($this->kelas)
            ->whereNotIn('status_pondok', [StatusPondok::NONAKTIF, StatusPondok::KELUAR, StatusPondok::LULUS])
            ->whereNull('tanggal_lulus_pondok')
            ->orderBy('angkatan_pondok', 'DESC')
            ->orderBy('nama', 'ASC')
            ->get();

        // Step 3: Prepare the final JSON structure
        $results = [];

        foreach ($santris as $index => $santri) {
            $attendanceData = [];
            $statusCounts = [
                'hadir' => 0,
                'telat' => 0,
                'alpa' => 0,
                'sakit' => 0,
                'izin' => 0,
            ];

            foreach ($distinctTanggalSesi as $tanggal => $sesis) {
                $attendanceData[$tanggal] = [
                    'sesi' => []
                ];

                foreach ($sesis as $sesi) {
                    // Step 4: Get the attendance status for the user on the specific tanggal and sesi
                    $sesi = $sesi->value;
                    $presensi = PresensiKelas::join('jurnal_kelas', 'presensi_kelas.jurnal_kelas_id', '=', 'jurnal_kelas.id')
                        ->where('presensi_kelas.user_id', $santri->id)
                        ->where('jurnal_kelas.tanggal', $tanggal)
                        ->where('jurnal_kelas.sesi', $sesi)
                        ->first();

                    $status = $presensi ? $presensi->status_kehadiran->value : null;
                    $attendanceData[$tanggal]['sesi'][$sesi] = $status;

                    // Step 5: Count the status
                    if ($status) {
                        $statusCounts[$status]++;
                    }
                }
            }

            // Step 6: Calculate total meetings and percentages
            $totalMeetings = array_sum($statusCounts);
            $percentages = array_map(function ($count) use ($totalMeetings) {
                return $totalMeetings ? round(($count / $totalMeetings) * 100, 2) . '%' : '0%';
            }, $statusCounts);

            $results[] = [
                'no' => $index + 1,
                'id' => $santri->id,
                'nama' => $santri->nama,
                'kelas' => $santri->kelas,
                'tanggal' => $attendanceData,
                'jumlah' => $statusCounts,
                'total_pertemuan' => $totalMeetings,
                'persen' => $percentages,
            ];
        }

        $this->attendanceData = $results;
        $this->distinctTanggalSesi = $distinctTanggalSesi->toArray();
        $this->merekap = true;
    }

    public function exportRekapPresensi()
    {
        return Excel::download(new RekapPresensiExport(
            $this->attendanceData,
            $this->distinctTanggalSesi,
            $this->kelas,
            $this->jenis_kelamin,
            $this->bulan
        ), 'presensi-kelas-['. implode(',', $this->kelas).']-bulan-'.$this->bulan.'-'.$this->jenis_kelamin.'.xlsx');
    }
}

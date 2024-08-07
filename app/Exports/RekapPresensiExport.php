<?php

namespace App\Exports;

use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RekapPresensiExport implements FromView
{
    private $attendanceData = [];
    private $distinctTanggalSesi = [];
    private $kelas = [];
    private $jenis_kelamin = '';
    private $bulan = '';

    public function __construct($attendanceData, $distinctTanggalSesi, $kelas, $jenis_kelamin, $bulan)
    {
        $this->attendanceData = $attendanceData;
        $this->distinctTanggalSesi = $distinctTanggalSesi;
        $this->kelas = $kelas;
        $this->jenis_kelamin = $jenis_kelamin;
        $this->bulan = $bulan;
    }
    public function view(): View
    {
        return view('exports.form-presensi', [
            'attendanceData' => $this->attendanceData,
            'distinctTanggalSesi' => $this->distinctTanggalSesi,
            'kelas' => $this->kelas,
            'jenis_kelamin' => $this->jenis_kelamin,
            'bulan' => $this->bulan,
        ]);
    }
}

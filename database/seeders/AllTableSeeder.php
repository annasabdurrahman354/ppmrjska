<?php

namespace Database\Seeders;

use App\Models\DewanGuru;
use App\Models\JadwalMunaqosah;
use App\Models\JurnalKelas;
use App\Models\MateriHafalan;
use App\Models\MateriHimpunan;
use App\Models\MateriJuz;
use App\Models\MateriSurat;
use App\Models\MateriTambahan;
use App\Models\PenilaianMunaqosah;
use App\Models\PlotJadwalMunaqosah;
use App\Models\PresensiKelas;
use Illuminate\Database\Seeder;

class AllTableSeeder extends Seeder
{
    public function run()
    {
        MateriSurat::factory(10)->create();
        MateriHimpunan::factory(10)->create();
        MateriTambahan::factory(10)->create();
        MateriHafalan::factory(10)->create();

        for ($i = 1; $i <= 30; $i++) {
            $nama = "Juz " . $i;
            $halamanAwal = 1 + ($i - 1) * 20;
            $halamanAkhir = 21 + ($i - 1) * 20;
            if($i != 1){
                $halamanAwal = $halamanAwal + 1;
            }
            if($i == 30){
                $halamanAkhir = 604;
            }
            MateriJuz::create([
                'nama' => $nama,
                'halaman_awal' => $halamanAwal,
                'halaman_akhir' => $halamanAkhir,
            ]);
        }
    }
}


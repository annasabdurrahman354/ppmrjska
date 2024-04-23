<?php

namespace Database\Seeders;

use App\Models\DewanGuru;
use App\Models\JadwalMunaqosah;
use App\Models\JurnalKelas;
use App\Models\MateriHafalan;
use App\Models\MateriHimpunan;
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
        DewanGuru::factory(10)->create();
        MateriSurat::factory(10)->create();
        MateriHimpunan::factory(10)->create();
        MateriTambahan::factory(10)->create();
        MateriHafalan::factory(10)->create();
        //JurnalKelas::factory(10)->create();
        PresensiKelas::factory(10)->create();
        JadwalMunaqosah::factory(10)->create();
        PlotJadwalMunaqosah::factory(10)->create();
        PenilaianMunaqosah::factory(10)->create();
    }
}


<?php

namespace Database\Seeders;

use App\Models\MateriJuz;
use Illuminate\Database\Seeder;

class MateriTableSeeder extends Seeder
{
    public function run()
    {
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


<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BiodataSantri;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use App\Models\Provinsi;
use App\Models\User;

class BiodataSantriFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BiodataSantri::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $provinsi = Provinsi::factory()->create();
        $kota = Kota::factory()->create(['provinsi_id' => $provinsi->id]);
        $kecamatan = Kecamatan::factory()->create(['kota_id' => $kota->id]);
        $kelurahan = Kelurahan::factory()->create(['kecamatan_id' => $kecamatan->id]);

        return [
            'user_id' => User::factory(),
            'tahun_pendaftaran' => $this->faker->numberBetween(2021, 2023),

            'nik' => $this->faker->regexify('[0-9]{16}'),
            'tempat_lahir_id' => $kota->id,
            'tanggal_lahir' => $this->faker->date(),
            'kewarganegaraan' => $this->faker->randomElement(["wni","wna"]),
            'golongan_darah' => $this->faker->randomElement(["a","b","ab","o","belum diketahui"]),
            'ukuran_baju' => $this->faker->randomElement(["s","m","l","xl","xxl","xxxl"]),

            'pendidikan_terakhir' => $this->faker->randomElement(["sma","paket c","s1","s2","d2","d3","d4"]),
            'program_studi' => $this->faker->colorName(),
            'universitas' => $this->faker->colorName(),
            'angkatan_kuliah' => $this->faker->numberBetween(2021, 2023),
            'status_kuliah' => $this->faker->randomElement(["belum diterima","aktif","nonaktif","lulus","keluar"]),
            'tanggal_lulus_kuliah' => $this->faker->date(),

            'alamat' => $this->faker->streetAddress(),
            'kelurahan_id' => $kelurahan->id,
            'kecamatan_id' => $kecamatan->id,
            'kota_id' => $kota->id,
            'provinsi_id' => $provinsi->id,

            'asal_kelompok' => $this->faker->streetName(),
            'asal_desa' => $this->faker->streetName(),
            'asal_daerah' => $this->faker->streetName(),
            'mulai_mengaji' => $this->faker->randomElement(["lahir","paud","tk","sd","smp","sma","kuliah"]),
            'bahasa_makna' => $this->faker->randomElement(["indonesia","jawa","inggris"]),

            'status_pernikahan' => $this->faker->randomElement(["belum menikah","sudah menikah","cerai"]),
            'status_tinggal' => $this->faker->randomElement(["bersama orang tua","bersama wali","mandiri"]),
            'status_orangtua' => $this->faker->randomElement(["lengkap","yatim","piatu", "yatim piatu"]),
            'jumlah_saudara' => $this->faker->numberBetween(1, 8),
            'anak_nomor' => $this->faker->numberBetween(1, 8),

            'nama_ayah' => $this->faker->name('male'),
            'nomor_telepon_ayah' => $this->faker->regexify('[0-9]{10}'),
            'pekerjaan_ayah' => $this->faker->jobTitle(),
            'dapukan_ayah' => $this->faker->jobTitle(),
            'nama_ibu' => $this->faker->name('female'),
            'nomor_telepon_ibu' => $this->faker->regexify('[0-9]{10}'),
            'pekerjaan_ibu' => $this->faker->jobTitle(),
            'dapukan_ibu' => $this->faker->jobTitle(),
            'nama_wali' => $this->faker->name('male'),
            'nomor_telepon_wali' => $this->faker->regexify('[0-9]{10}'),
            'pekerjaan_wali' => $this->faker->jobTitle(),
            'dapukan_wali' => $this->faker->jobTitle(),
            'hubungan_wali' => $this->faker->randomElement(["orang tua", "saudara", "kerabat", "nonkerabat"]),
        ];
    }
}

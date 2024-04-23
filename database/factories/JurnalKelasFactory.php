<?php

namespace Database\Factories;

use App\Models\DewanGuru;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\JurnalKelas;
use App\Models\MateriHimpunan;
use App\Models\MateriJuz;
use App\Models\MateriSurat;
use App\Models\MateriTambahan;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;

class JurnalKelasFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JurnalKelas::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $materi = $this->faker->randomElement([
            
        ]);

        return [
            'kelas' => $this->faker->randomElement([['2021'],['2022'],['2023'],['2021', '2022', '2023', 'Takmili'], ['2021', '2022']]),
            'jenis_kelamin' => $this->faker->randomElement(["laki-laki","perempuan"]),
            'tanggal' => $this->faker->date(),
            'sesi' => $this->faker->randomElement(["subuh","pagi_1","pagi_2","siang","malam"]),
            'dewan_guru_id' => DewanGuru::factory(),
            'dewan_guru_type' => DewanGuru::class,
            'materi_awal_id' => MateriSurat::factory(),
            'materi_akhir_id' => MateriSurat::factory(),
            'materi_awal_type' => MateriSurat::class,
            'materi_akhir_type' => MateriSurat::class,
            'halaman_awal' => $this->faker->numberBetween(1, 1000),
            'halaman_akhir' => $this->faker->numberBetween(1, 1000),
            'ayat_awal' => $this->faker->numberBetween(1, 1000),
            'ayat_akhir' => $this->faker->numberBetween(1, 1000),
            'link_rekaman' => $this->faker->url(),
            'keterangan' => $this->faker->word(),
            'perekap_id' => User::factory(),
        ];
    }
}

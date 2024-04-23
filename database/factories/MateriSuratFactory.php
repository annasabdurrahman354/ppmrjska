<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\MateriSurat;

class MateriSuratFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MateriSurat::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nomor' => $this->faker->numberBetween(1, 114),
            'nama' => $this->faker->userName(),
            'jumlah_ayat' => $this->faker->numberBetween(1, 120),
            'jumlah_halaman' => $this->faker->numberBetween(1, 50),
            'halaman_awal' => $this->faker->numberBetween(1, 100),
            'halaman_akhir' => $this->faker->numberBetween(101, 200),
        ];
    }
}

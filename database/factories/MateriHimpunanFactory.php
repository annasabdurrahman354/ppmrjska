<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\MateriHimpunan;

class MateriHimpunanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MateriHimpunan::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->userName(),
            'jumlah_halaman' => $this->faker->numberBetween(1, 200),
            'halaman_awal' => $this->faker->numberBetween(1, 100),
            'halaman_akhir' => $this->faker->numberBetween(101, 200),
        ];
    }
}

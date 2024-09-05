<?php

namespace Database\Factories;

use App\Models\AngkatanPondok;
use Illuminate\Database\Eloquent\Factories\Factory;

class AngkatanPondokFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AngkatanPondok::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'angkatan_pondok' => $this->faker->numberBetween(2021, 2023),
            'kelas' => $this->faker->randomElement(["2021", "2022", '2023', "Takmili"]),
            'tanggal_masuk_takmili' => now(),
        ];
    }
}

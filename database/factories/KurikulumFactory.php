<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Kurikulum;

class KurikulumFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Kurikulum::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'angkatan_pondok' => $this->faker->numberBetween(2020, 2023),
            'plot_kurikulum' => [],
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\MateriHafalan;
use Illuminate\Database\Eloquent\Factories\Factory;

class MateriHafalanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MateriHafalan::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->userName(),
        ];
    }
}

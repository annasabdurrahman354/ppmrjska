<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\DewanGuru;

class DewanGuruFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DewanGuru::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->name(),
            'nama_panggilan' => $this->faker->firstNameMale(),
            'nomor_telepon' => $this->faker->regexify('[0-9]{13}'),
            'email' => $this->faker->safeEmail(),
            'alamat' => $this->faker->word(),
            'status_aktif' => $this->faker->boolean(),
        ];
    }
}

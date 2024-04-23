<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\JadwalMunaqosah;
use App\Models\MateriMunaqosah;

class JadwalMunaqosahFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JadwalMunaqosah::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'materi_munaqosah_id' => MateriMunaqosah::factory(),
            'waktu' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'maksimal_pendaftar' => $this->faker->numberBetween(4, 8),
            'batas_awal_pendaftaran' => now(),
            'batas_akhir_pendaftaran' => $this->faker->dateTimeBetween('now', '+1 month'),
        ];
    }
}

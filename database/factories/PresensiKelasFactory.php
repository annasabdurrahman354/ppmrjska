<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\JurnalKela;
use App\Models\JurnalKelas;
use App\Models\PresensiKelas;
use App\Models\User;

class PresensiKelasFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PresensiKelas::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'jurnal_kelas_id' => JurnalKelas::factory(),
            'user_id' => User::factory(),
            'status_kehadiran' => $this->faker->randomElement(["hadir","telat","izin","sakit","alpa"]),
        ];
    }
}

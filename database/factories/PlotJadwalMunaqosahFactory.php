<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\JadwalMunaqosah;
use App\Models\PlotJadwalMunaqosah;
use App\Models\User;

class PlotJadwalMunaqosahFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PlotJadwalMunaqosah::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'jadwal_munaqosah_id' => JadwalMunaqosah::factory(),
            'user_id' => User::factory(),
        ];
    }
}

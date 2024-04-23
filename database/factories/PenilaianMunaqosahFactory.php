<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\MateriMunaqosah;
use App\Models\PenilaianMunaqosah;
use App\Models\User;

class PenilaianMunaqosahFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PenilaianMunaqosah::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'materi_munaqosah_id' => MateriMunaqosah::factory(),
            'nilai_materi' => '{}',
            'nilai_hafalan' => '{}',
        ];
    }
}

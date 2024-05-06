<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\DewanGuru;
use App\Models\MateriHafalan;
use App\Models\MateriHimpunan;
use App\Models\MateriMunaqosah;
use App\Models\MateriSurat;

class MateriMunaqosahFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MateriMunaqosah::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'kelas' => $this->faker->randomElement(["2021","2022","2023","Takmili"]),
            'semester' => $this->faker->numberBetween(1, 8),
            'tahun_ajaran' => $this->faker->regexify('[A-Za-z0-9]{9}'),
            'jenis_materi' => $this->faker->randomElement([MateriSurat::class, MateriHimpunan::class, MateriHafalan::class]),
            'materi' => '{}',
            'detail' => $this->faker->word(),
            'hafalan' => '{}',
            'indikator_materi' => '{}',
            'indikator_hafalan' => '{}',
            'dewan_guru_id' => DewanGuru::factory(),
        ];
    }
}

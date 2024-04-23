<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Kurikulum;
use App\Models\MateriSurat;
use App\Models\PlotKurikulum;

class PlotKurikulumFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PlotKurikulum::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'kurikulum_id' => Kurikulum::factory(),
            'materi_type' => MateriSurat::class,
            'materi_id' => MateriSurat::factory(),
            'status_tercapai' => false,
        ];
    }
}

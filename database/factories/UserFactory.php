<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;
    protected static ?string $password;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->name(),
            'nama_panggilan' => $this->faker->firstName(),
            'jenis_kelamin' => $this->faker->randomElement(["laki-laki","perempuan"]),
            'nis' => $this->faker->regexify('[A-Za-z0-9]{9}'),
            'nomor_telepon' => $this->faker->regexify('[0-9]{13}'),
            'email' => $this->faker->safeEmail(),
            'kelas' => $this->faker->randomElement(["2021","2022","2023","Takmili"]),
            'angkatan_pondok' => $this->faker->numberBetween(2021, 2023),
            'status_pondok' => $this->faker->randomElement(["aktif", "sambang", "keluar", "lulus"]),
            'tanggal_lulus_pondok' => null,
            'password' => static::$password ??= Hash::make('password'),
            'email_verified_at' => now(),
        ];
    }
}

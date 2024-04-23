<?php

namespace Database\Seeders;

use App\Models\BiodataSantri;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $sid = Str::ulid();
        DB::table('users')->insert([
            'id' => $sid,
            'email' => 'superadmin@ppmrjska.web.id',
            'email_verified_at' => now(),
            'password' => Hash::make('superadmin'),
            'created_at' => now(),
            'updated_at' => now(),
            'nama' => 'Super Admin',
            'nama_panggilan' => "Super Admin",
            'jenis_kelamin' => "laki-laki",
            'nis' => "0",
            'nomor_telepon' => "0",
            'kelas' => 'admin',
            'angkatan_pondok' => 0,
            'status_pondok' => "aktif",
            'tanggal_lulus_pondok' => null,
        ]);

        Artisan::call('shield:super-admin', ['--user' => $sid]);    

        $sid = Str::ulid();
        DB::table('users')->insert([
            'id' => $sid,
            'email' => 'annasabd@ppmrjska.web.id',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
            'nama' => 'Annas Abdurrahman',
            'nama_panggilan' => "Annas",
            'jenis_kelamin' => "laki-laki",
            'nis' => "09283",
            'nomor_telepon' => "012131121212",
            'kelas' => '2023',
            'angkatan_pondok' => 2023,
            'status_pondok' => "aktif",
            'tanggal_lulus_pondok' => null,
        ]);

        User::factory(10)->create();
        BiodataSantri::factory(10)->create();
        // Bind superadmin to FilamentShiled
    }
}


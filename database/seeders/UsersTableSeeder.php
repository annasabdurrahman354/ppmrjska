<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\BiodataSantri;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $sid = Str::ulid();

        DB::table('users')->insert([
            'id' => $sid,
            'email' => 'admin@ppmrjska.web.id',
            'email_verified_at' => now(),
            'password' => Hash::make('adminadmin'),
            'created_at' => now(),
            'updated_at' => now(),
            'nama' => 'Admin',
            'nama_panggilan' => "Admin",
            'jenis_kelamin' => "laki-laki",
            'nis' => "0",
            'nomor_telepon' => "0",
            'angkatan_pondok' => 999999,
            'status_pondok' => "aktif",
            'tanggal_lulus_pondok' => null,
        ]);

        Artisan::call('shield:super-admin', ['--user' => $sid]);
        User::find($sid)->update([
            'angkatan_pondok' => 0,
        ]);

        DB::table('angkatan_pondok')->insert([
            [
                'angkatan_pondok' => 2018,
                'kelas' => "Takmili",
                'tanggal_masuk_takmili' => now(),
            ],
            [
                'angkatan_pondok' => 2019,
                'kelas' => "Takmili",
                'tanggal_masuk_takmili' => now(),
            ],
            [
                'angkatan_pondok' => 2020,
                'kelas' => "Takmili",
                'tanggal_masuk_takmili' => now(),
            ],
            [
                'angkatan_pondok' => 2021,
                'kelas' => "Takmili",
                'tanggal_masuk_takmili' => now(),
            ],
            [
                'angkatan_pondok' => 2022,
                'kelas' => "2022",
                'tanggal_masuk_takmili' => null,
            ],
            [
                'angkatan_pondok' => 2023,
                'kelas' => "2023",
                'tanggal_masuk_takmili' => null,
            ],
            [
                'angkatan_pondok' => 2024,
                'kelas' => "2024",
                'tanggal_masuk_takmili' => null,
            ]
        ]);
        /*
        BiodataSantri::factory(2)->create()->each(function ($biodata) {
            $biodata->user->assignRole(Role::SANTRI);
            Artisan::call('shield:super-admin', ['--user' => $biodata->user->id]);
        });
        BiodataSantri::factory(50)->create()->each(function ($biodata) {
            $biodata->user->assignRole(Role::SANTRI);
        });
        BiodataSantri::factory(6)->create()->each(function ($biodata) {
            $biodata->user->assignRole(Role::SANTRI);
            $biodata->user->assignRole(Role::DMC_PASUS_KEILMUAN);
        });
        BiodataSantri::factory(6)->create()->each(function ($biodata) {
            $biodata->user->assignRole(Role::SANTRI);
            $biodata->user->assignRole(Role::DMC_PASUS_KEDISIPLINAN);
        });
        BiodataSantri::factory(12)->create()->each(function ($biodata) {
            $biodata->user->assignRole(Role::SANTRI);
            $biodata->user->assignRole(Role::KETUA_KELAS);
        });
        */
    }
}


<?php

namespace Database\Seeders;

use App\Models\BiodataSantri;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use App\Models\Provinsi;
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
            'kelas' => '',
            'angkatan_pondok' => 0,
            'status_pondok' => "aktif",
            'tanggal_lulus_pondok' => null,
        ]);

        Artisan::call('shield:super-admin', ['--user' => $sid]);
        User::find($sid)->update(['kelas' => config('filament-shield.super_admin.name')]);

        BiodataSantri::factory(2)->create()->each(function ($biodata) {
            $biodata->user->assignRole('santri');
            Artisan::call('shield:super-admin', ['--user' => $biodata->user->id]);
        });
        BiodataSantri::factory(50)->create()->each(function ($biodata) {
            $biodata->user->assignRole('santri');
        });
        BiodataSantri::factory(6)->create()->each(function ($biodata) {
            $biodata->user->assignRole('santri');
            $biodata->user->assignRole('dmcp_keilmuan');
        });
        BiodataSantri::factory(12)->create()->each(function ($biodata) {
            $biodata->user->assignRole('santri');
            $biodata->user->assignRole('ketua_kelas');
        });
    }
}


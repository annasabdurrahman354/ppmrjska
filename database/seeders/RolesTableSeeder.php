<?php

namespace Database\Seeders;

use App\Enums\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn(PHP_EOL . 'Creating roles..');

        DB::table('roles')->insert(
            [
                'name' => Role::DMC_PASUS_KEILMUAN,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('roles')->insert(
            [
                'name' => Role::TIM_KEILMUAN,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('roles')->insert(
            [
                'name' => Role::DMC_PASUS_KEDISIPLINAN,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('roles')->insert(
            [
                'name' => Role::DMC_PASUS_KOORDINATOR,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('roles')->insert(
            [
                'name' => Role::DMC_PASUS_SEKRETARIS,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        //DB::table('roles')->insert(
        //    [
        //        'name' => 'dmcp_kedisiplinan',
        //       'guard_name' => 'web',
        //        'created_at' => now(),
        //        'updated_at' => now(),
        //    ]
        //);

        //DB::table('roles')->insert(
        //    [
        //        'name' => 'dmcp_sekretaris',
        //        'guard_name' => 'web',
        //        'created_at' => now(),
        //        'updated_at' => now(),
        //    ]
        //);

        DB::table('roles')->insert(
            [
                'name' => Role::KETUA_KELAS,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('roles')->insert(
            [
                'name' => Role::SANTRI,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info(PHP_EOL . 'Done Creating roles..');
    }
}

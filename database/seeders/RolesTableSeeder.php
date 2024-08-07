<?php

namespace Database\Seeders;

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
                'name' => 'super_admin',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('roles')->insert(
            [
                'name' => 'dmcp_keilmuan',
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
                'name' => 'ketua_kelas',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('roles')->insert(
            [
                'name' => 'santri',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info(PHP_EOL . 'Done Creating roles..');
    }
}

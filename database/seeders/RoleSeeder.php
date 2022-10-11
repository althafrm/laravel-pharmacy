<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();

        $now = now();
        $roles = [
            ['id' => 1, 'name' => 'ADMIN', 'created_at' => $now],
            ['id' => 2, 'name' => 'PHARMACY', 'created_at' => $now],
            ['id' => 3, 'name' => 'USER', 'created_at' => $now],
        ];

        DB::table('roles')->insert($roles);
    }
}

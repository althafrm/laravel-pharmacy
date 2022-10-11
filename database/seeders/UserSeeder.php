<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        $password = Hash::make('password');
        $now = now();
        $users = [
            [
                'id' => 1,
                'role_id' => 1,
                'name' => 'admin',
                'email' => 'admin@pharma.cy',
                'password' => $password,
                'address' => 'Address',
                'contact_no' => '0123456789',
                'dob' => '1993-01-01',
                'created_at' => $now,
            ],
            [
                'id' => 2,
                'role_id' => 2,
                'name' => 'pharmacy1',
                'email' => 'p1@pharma.cy',
                'password' => $password,
                'address' => 'Address',
                'contact_no' => '0123456789',
                'dob' => '1993-01-01',
                'created_at' => $now,
            ],
            [
                'id' => 3,
                'role_id' => 2,
                'name' => 'pharmacy2',
                'email' => 'p2@pharma.cy',
                'password' => $password,
                'address' => 'Address',
                'contact_no' => '0123456789',
                'dob' => '1993-01-01',
                'created_at' => $now,
            ],
            [
                'id' => 4,
                'role_id' => 3,
                'name' => 'user1',
                'email' => 'u1@pharma.cy',
                'password' => $password,
                'address' => 'Address',
                'contact_no' => '0123456789',
                'dob' => '1993-01-01',
                'created_at' => $now,
            ],
            [
                'id' => 5,
                'role_id' => 3,
                'name' => 'user2',
                'email' => 'u2@pharma.cy',
                'password' => $password,
                'address' => 'Address',
                'contact_no' => '0123456789',
                'dob' => '1993-01-01',
                'created_at' => $now,
            ],
        ];

        DB::table('users')->insert($users);
    }
}

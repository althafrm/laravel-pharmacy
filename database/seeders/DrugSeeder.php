<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DrugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('drugs')->delete();

        $now = now();
        $drugs = [
            ['id' => 1, 'name' => 'Amoxicillin 250mg', 'price' => '10.00', 'created_at' => $now],
            ['id' => 2, 'name' => 'Paracetamol 500mg', 'price' => '5.00', 'created_at' => $now],
            ['id' => 3, 'name' => 'Atorvastatin 10mg', 'price' => '4.50', 'created_at' => $now],
            ['id' => 4, 'name' => 'Lisinopril 5mg', 'price' => '7.50', 'created_at' => $now],
            ['id' => 5, 'name' => 'Azithromycin 250mg', 'price' => '55.00', 'created_at' => $now],
            ['id' => 6, 'name' => 'Simvastatin 10mg', 'price' => '6.50', 'created_at' => $now],
            ['id' => 7, 'name' => 'Montelukast 4mg', 'price' => '4.00', 'created_at' => $now],
        ];

        DB::table('drugs')->insert($drugs);
    }
}

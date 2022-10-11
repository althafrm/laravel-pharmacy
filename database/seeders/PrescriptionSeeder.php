<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('prescriptions')->delete();

        $now = now();
        $prescriptions = [
            [
                'id' => 1,
                'images' => '["public\/test\/prescription1.png","public\/test\/prescription2.png","public\/test\/prescription3.png"]',
                'note' => null,
                'delivery_address' => 'Delivery Address 1',
                'delivery_time_from' => '15:00:00',
                'delivery_time_to' => '17:00:00',
                'has_quotation' => 0,
                'created_by' => 4,
                'created_at' => $now,
            ],
            [
                'id' => 2,
                'images' => '["public\/test\/prescription1.png","public\/test\/prescription2.png","public\/test\/prescription3.png"]',
                'note' => 'Note 2',
                'delivery_address' => 'Delivery Address 2',
                'delivery_time_from' => '16:00:00',
                'delivery_time_to' => '18:30:00',
                'has_quotation' => 0,
                'created_by' => 5,
                'created_at' => $now,
            ],
        ];

        DB::table('prescriptions')->insert($prescriptions);
    }
}

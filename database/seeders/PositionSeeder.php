<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            1 => 'President',
            2 => 'Vice President',
            3 => 'Secretary',
            4 => 'Treasurer',
            5 => 'Auditor',
            6 => 'PIO',
            7 => 'Business Manager',
        ];

        foreach ($positions as $id => $name) {
            DB::table('positions')->insert([
                'position_id' => $id,
                'position_name' => $name,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}

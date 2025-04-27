<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            'President',
            'Vice President',
            'Secretary',
            'Treasurer',
            'Auditor',
            'PIO',
            'Business Manager',
        ];

        foreach ($positions as $position) {
            DB::table('positions')->insert([
                'name' => $position,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

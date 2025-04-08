<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    public function run()
    {
        $positions = [
            'President',
            'Vice President',
            'Secretary',
            'Treasurer',
            'Auditor',
            'Student PIO',
            'Business Manager'
        ];

        foreach ($positions as $position) {
            Position::create([
                'name' => $position
            ]);
        }
    }
} 
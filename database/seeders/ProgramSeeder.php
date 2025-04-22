<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            'BS in Information Technology',
            'BS in Computer Science',
            'BS in Information Systems',
            'Bachelor of Library and Information Science',
            'BS in Entertainment and Multimedia Computing - Digital Animation',
            'BS in Entertainment and Multimedia Computing - Game Development',
            'Bachelor of Multimedia Arts',
        ];

        foreach ($programs as $program) {
            DB::table('programs')->insert([
                'name' => $program,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

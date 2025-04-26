<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartylistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $partylist = [
            'BugsayCoders',
            'TechnoPreneurs'
        ];


        foreach ($partylist as $partylist) {
            DB::table('partylists')->insert([
                'partylist_name' => $partylist,
                'election_year' => '2025',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

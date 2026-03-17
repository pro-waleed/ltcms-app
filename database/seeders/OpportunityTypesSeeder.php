<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OpportunityTypesSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'دبلوم', 'description' => 'برامج دبلوم'],
            ['name' => 'دورة قصيرة', 'description' => 'دورات قصيرة'],
            ['name' => 'ورشة عمل', 'description' => 'ورش عمل'],
            ['name' => 'منحة تدريبية', 'description' => 'منح تدريبية'],
        ];

        foreach ($rows as $row) {
            DB::table('opportunity_types')->updateOrInsert(
                ['name' => $row['name']],
                ['description' => $row['description']]
            );
        }
    }
}

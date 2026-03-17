<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'system_admin', 'description' => 'مدير النظام'],
            ['name' => 'training_manager', 'description' => 'مسؤول التدريب'],
            ['name' => 'data_entry', 'description' => 'موظف إدخال/متابعة'],
            ['name' => 'viewer', 'description' => 'مستخدم استعلام/قيادة'],
        ];

        foreach ($rows as $row) {
            DB::table('roles')->updateOrInsert(
                ['name' => $row['name']],
                ['description' => $row['description']]
            );
        }
    }
}

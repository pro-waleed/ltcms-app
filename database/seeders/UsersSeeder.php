<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->updateOrInsert(
            ['username' => 'admin'],
            [
                'full_name' => 'مدير النظام',
                'email' => 'admin@ltcms.local',
                'password' => Hash::make('Admin@2026'),
                'is_active' => 1,
            ]
        );

        $adminId = DB::table('users')->where('username', 'admin')->value('id');
        $roleId = DB::table('roles')->where('name', 'system_admin')->value('id');
        if ($adminId && $roleId) {
            DB::table('role_user')->updateOrInsert(
                ['role_id' => $roleId, 'user_id' => $adminId],
                []
            );
        }
    }
}

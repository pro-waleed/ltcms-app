<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $admin = DB::table('users')->where('username', 'admin')->first();

        if (!$admin) {
            $adminId = DB::table('users')->insertGetId([
                'username' => 'admin',
                'full_name' => 'مدير النظام',
                'email' => 'admin@ltcms.local',
                'password' => Hash::make('Admin@2026'),
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            DB::table('users')
                ->where('id', $admin->id)
                ->update([
                    'is_active' => 1,
                    'updated_at' => now(),
                ]);

            $adminId = $admin->id;
        }

        $roleId = DB::table('roles')->where('name', 'system_admin')->value('id');
        if ($adminId && $roleId) {
            DB::table('role_user')->updateOrInsert(
                ['role_id' => $roleId, 'user_id' => $adminId],
                []
            );
        }
    }
}

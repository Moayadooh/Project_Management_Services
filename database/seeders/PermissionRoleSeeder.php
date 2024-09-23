<?php

namespace Database\Seeders;

use App\Models\PermissionRole;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    public function run(): void
    {
        PermissionRole::create(['permission_id' => 1, 'role_id' => 1]);
        PermissionRole::create(['permission_id' => 2, 'role_id' => 1]);
        PermissionRole::create(['permission_id' => 3, 'role_id' => 1]);
    }
}

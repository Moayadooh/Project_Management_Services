<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        Permission::create(['name' => 'add']);
        Permission::create(['name' => 'update']);
        Permission::create(['name' => 'delete']);
    }
}

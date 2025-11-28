<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Permission::create(['name' => 'manage_events']);
        Permission::create(['name' => 'resource_hub']);
        Permission::create(['name' => 'view_students']);

        $adminRole = Role::create(['name' => 'admin']);
        $supervisorRole = Role::create(['name' => 'supervisor']);

        $adminRole->givePermissionTo(Permission::all());
        $supervisorRole->givePermissionTo(['manage_events', 'view_students']);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@mail.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole($adminRole);

        $supervisor = User::create([
            'name' => 'Supervisor User',
            'email' => 'supervisor@mail.com',
            'password' => bcrypt('password'),
        ]);
        $supervisor->assignRole($supervisorRole);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $adminEmail = 'admin@example.com';
        $adminPassword = 'password123'; // Change securely later

        $user = User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Admin User',
                'password' => Hash::make($adminPassword),
            ]
        );

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $user->assignRole($adminRole);

        $this->command->info("Admin created:\nEmail: {$adminEmail}\nPassword: {$adminPassword}");
    }
}

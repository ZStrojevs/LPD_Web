<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $adminEmail = 'admin@example.com'; // define the admin email here

        if (!User::where('email', $adminEmail)->exists()) {
            User::create([
                'name' => 'Admin User',
                'email' => $adminEmail,
                'password' => Hash::make('1345Me'), // replace 'yourpassword' with a secure password
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
            $this->command->info('Admin user created.');
        } else {
            $this->command->info('Admin user already exists.');
        }
    }
}

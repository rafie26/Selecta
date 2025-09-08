<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin user already exists
        $adminExists = User::where('email', 'admin@selecta.com')->exists();
        
        if (!$adminExists) {
            User::create([
                'name' => 'Admin Selecta',
                'email' => 'admin@selecta.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '+6281234567890',
                'phone_code' => '+62',
            ]);
            
            echo "Admin user created successfully!\n";
        } else {
            // Update existing user to admin role if needed
            $user = User::where('email', 'admin@selecta.com')->first();
            if ($user->role !== 'admin') {
                $user->update(['role' => 'admin']);
                echo "Existing user updated to admin role!\n";
            } else {
                echo "Admin user already exists!\n";
            }
        }
    }
}

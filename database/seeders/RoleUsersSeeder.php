<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::updateOrCreate(
            ['email' => 'admin@selecta.com'],
            [
                'name' => 'Admin Selecta',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'phone' => '081234567890',
                'phone_code' => '+62',
            ]
        );

        // Petugas Loket
        User::updateOrCreate(
            ['email' => 'loket@selecta.com'],
            [
                'name' => 'Petugas Loket',
                'password' => Hash::make('password'),
                'role' => User::ROLE_PETUGAS_LOKET,
                'phone' => '081234567891',
                'phone_code' => '+62',
            ]
        );

        // Petugas Hotel
        User::updateOrCreate(
            ['email' => 'hotel@selecta.com'],
            [
                'name' => 'Petugas Hotel',
                'password' => Hash::make('password'),
                'role' => User::ROLE_PETUGAS_HOTEL,
                'phone' => '081234567892',
                'phone_code' => '+62',
            ]
        );

        // Regular User
        User::updateOrCreate(
            ['email' => 'user@selecta.com'],
            [
                'name' => 'User Biasa',
                'password' => Hash::make('password'),
                'role' => User::ROLE_USER,
                'phone' => '081234567893',
                'phone_code' => '+62',
            ]
        );

        $this->command->info('✅ Role users created successfully!');
        $this->command->info('');
        $this->command->info('Login credentials:');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('👤 Admin:');
        $this->command->info('   Email: admin@selecta.com');
        $this->command->info('   Password: password');
        $this->command->info('   URL: /admin/login');
        $this->command->info('');
        $this->command->info('🎫 Petugas Loket:');
        $this->command->info('   Email: loket@selecta.com');
        $this->command->info('   Password: password');
        $this->command->info('   URL: /login');
        $this->command->info('');
        $this->command->info('🏨 Petugas Hotel:');
        $this->command->info('   Email: hotel@selecta.com');
        $this->command->info('   Password: password');
        $this->command->info('   URL: /login');
        $this->command->info('');
        $this->command->info('👥 Regular User:');
        $this->command->info('   Email: user@selecta.com');
        $this->command->info('   Password: password');
        $this->command->info('   URL: /login');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }
}

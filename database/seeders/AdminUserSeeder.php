<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@smecomics.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('newpassword'),
                'is_admin' => true,
            ]
        );

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@smecomics.com');
        $this->command->info('Password: newpassword');
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Admin',
                'role' => 'admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password')
            ],
            [
                'name' => 'User',
                'role' => 'user',
                'email' => 'user@user.com',
                'password' => Hash::make('password')
            ]
        ];

        User::query()->insert($users);
    }
}

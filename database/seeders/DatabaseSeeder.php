<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => 'password',
                'role' => User::ROLE_ADMIN,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Test User',
                'password' => 'password',
                'role' => User::ROLE_USER,
                'email_verified_at' => now(),
            ]
        );

        $this->call([
            CategorySeeder::class,
            RestaurantSeeder::class,
            RestaurantCategorySeeder::class,
            ProductSeeder::class,
            AddressSeeder::class,
            FavoriteSeeder::class,
            ReviewSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
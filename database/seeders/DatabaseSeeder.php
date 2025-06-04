<?php

namespace Database\Seeders;

use App\Models\User; // Make sure User model is imported
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Import Hash facade for passwords

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- Create Specific Users for Testing/Admin ---

        // Admin User
        User::updateOrCreate(
            ['email' => 'admin@example.com'], // Find by email to prevent duplicates if seeder runs multiple times
            [
                'name' => 'Admin User',
                'password' => 'password', // Laravel will hash this due to the 'hashed' cast in User model
                                          // Or use Hash::make('password') if you prefer explicit hashing here
                'role' => User::ROLE_ADMIN, // Assumes ROLE_ADMIN constant is defined in User model
                'email_verified_at' => now(), // Optional: mark as verified
            ]
        );

        // Regular Test User (for Playwright tests that need a non-admin user)
        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Test User', // This matches the 'userName' in your Playwright tests
                'password' => 'password', // This matches 'userPassword' in Playwright tests
                'role' => User::ROLE_USER, // Assumes ROLE_USER constant is defined in User model
                'email_verified_at' => now(),
            ]
        );

        // You can still create additional random users using the factory if needed for other testing
        // User::factory(5)->create(); // Example: creates 5 more random users

        // --- Call Other Seeders ---
        // Ensure these seeders exist and are correctly implemented
        $this->call([
            CategorySeeder::class,
            RestaurantSeeder::class,
            RestaurantCategorySeeder::class, // Links restaurants and categories
            ProductSeeder::class,          // Needs categories and restaurants
            AddressSeeder::class,          // Needs users
            FavoriteSeeder::class,         // Needs users and products
            ReviewSeeder::class,           // Needs users and products
            OrderSeeder::class,            // Needs users, products, addresses
            // PaymentSeeder would be called after OrderSeeder or integrated
        ]);
    }
}
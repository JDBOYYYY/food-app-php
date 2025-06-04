<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Restaurant;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch some existing categories and restaurants (assuming they were seeded)
        $pizzaCategory = Category::where('Name', 'Pizzas')->first();
        $burgersCategory = Category::where('Name', 'Burgery')->first();
        $mainCoursesCategory = Category::where('Name', 'Dania główne')->first(); // Main Courses

        $luigisRestaurant = Restaurant::where('Name', 'Luigi\'s Pizza Place')->first();
        $sushiExpress = Restaurant::where('Name', 'Sushi Express')->first(); // Example

        if (!$pizzaCategory || !$burgersCategory || !$luigisRestaurant || !$mainCoursesCategory) {
            $this->command->warn('Required categories or restaurants not found for ProductSeeder. Skipping some products.');
            // You might want to create them here if they don't exist, or ensure seeders run in order.
        }

        if ($luigisRestaurant && $pizzaCategory) {
            Product::updateOrCreate(
                ['Name' => 'Margherita Pizza', 'RestaurantId' => $luigisRestaurant->Id],
                [
                    'Description' => 'Classic delight with fresh mozzarella and basil.',
                    'Price' => 12.99,
                    'ImageUrl' => 'http://example.com/images/margherita.jpg',
                    'CategoryId' => $pizzaCategory->Id,
                ]
            );
            Product::updateOrCreate(
                ['Name' => 'Pepperoni Passion', 'RestaurantId' => $luigisRestaurant->Id],
                [
                    'Description' => 'Loaded with spicy pepperoni.',
                    'Price' => 14.50,
                    'ImageUrl' => 'http://example.com/images/pepperoni.jpg',
                    'CategoryId' => $pizzaCategory->Id,
                ]
            );
        }

        if ($luigisRestaurant && $mainCoursesCategory) {
             Product::updateOrCreate(
                ['Name' => 'Spaghetti Carbonara', 'RestaurantId' => $luigisRestaurant->Id],
                [
                    'Description' => 'Creamy pasta with pancetta and pecorino.',
                    'Price' => 16.00,
                    'ImageUrl' => 'http://example.com/images/carbonara.jpg',
                    'CategoryId' => $mainCoursesCategory->Id,
                ]
            );
        }


        // Example for another restaurant and category
        if ($sushiExpress && $burgersCategory) { // Let's pretend Sushi Express also sells a "Sushi Burger"
            Product::updateOrCreate(
                ['Name' => 'Deluxe Sushi Burger', 'RestaurantId' => $sushiExpress->Id],
                [
                    'Description' => 'A unique fusion burger with a sushi twist.',
                    'Price' => 18.99,
                    'ImageUrl' => 'http://example.com/images/sushiburger.jpg',
                    'CategoryId' => $burgersCategory->Id, // Assuming Burgers category exists
                ]
            );
        }

        // Add more products as needed
        $this->command->info('Products seeded.');
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class RestaurantCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Clear the pivot table to ensure a clean slate on re-seeding
        DB::table('RestaurantCategories')->truncate();

        // Get all categories and restaurants from the database
        $categories = Category::all()->keyBy('Name');
        $restaurants = Restaurant::all();

        foreach ($restaurants as $restaurant) {
            $assignedCategories = [];

            // Assign categories based on keywords in the restaurant's name
            if (str_contains(strtolower($restaurant->Name), 'pizza')) {
                if (isset($categories['Pizzas'])) {
                    $assignedCategories[] = $categories['Pizzas']->Id;
                }
            }
            if (str_contains(strtolower($restaurant->Name), 'burger')) {
                if (isset($categories['Burgery'])) {
                    $assignedCategories[] = $categories['Burgery']->Id;
                }
            }
            if (str_contains(strtolower($restaurant->Name), 'sushi')) {
                if (isset($categories['Przystawki'])) { // Assuming Sushi is an appetizer category for now
                    $assignedCategories[] = $categories['Przystawki']->Id;
                }
            }
            if (str_contains(strtolower($restaurant->Name), 'ramen')) {
                 if (isset($categories['Zupy'])) {
                    $assignedCategories[] = $categories['Zupy']->Id;
                }
            }
            if (str_contains(strtolower($restaurant->Name), 'thai') || str_contains(strtolower($restaurant->Name), 'indian')) {
                 if (isset($categories['Dania główne'])) {
                    $assignedCategories[] = $categories['Dania główne']->Id;
                }
            }
             if (str_contains(strtolower($restaurant->Name), 'vegan')) {
                 if (isset($categories['Sałatki'])) {
                    $assignedCategories[] = $categories['Sałatki']->Id;
                }
            }
            if (str_contains(strtolower($restaurant->Name), 'pancake')) {
                 if (isset($categories['Desery'])) {
                    $assignedCategories[] = $categories['Desery']->Id;
                }
            }

            // If no categories were assigned based on name, assign a default one
            if (empty($assignedCategories)) {
                if (isset($categories['Dania główne'])) {
                    $assignedCategories[] = $categories['Dania główne']->Id;
                }
            }

            // Attach the found categories to the restaurant
            // syncWithoutDetaching prevents errors if a category is already attached
            $restaurant->categories()->syncWithoutDetaching($assignedCategories);
        }
    }
}
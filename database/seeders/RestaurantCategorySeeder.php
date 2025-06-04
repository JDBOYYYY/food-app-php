<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;
use App\Models\Category;
use Illuminate\Support\Facades\DB; // For direct pivot table inserts

class RestaurantCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Example: Luigi's Pizza Place (Restaurant) belongs to Pizzas (Category) and Italian Food (Category)
        $luigis = Restaurant::where('Name', 'Luigi\'s Pizza Place')->first();
        $sushiExpress = Restaurant::where('Name', 'Sushi Express')->first(); // Assuming you seeded this

        $pizzasCategory = Category::where('Name', 'Pizzas')->first();
        $italianCategory = Category::where('Name', 'Dania główne')->first(); // Assuming 'Main Courses' is Italian
        $sushiCategory = Category::where('Name', 'Przystawki')->first(); // Or a more specific 'Sushi' category

        if ($luigis && $pizzasCategory) {
            // Option 1: Using Eloquent's attach() method (preferred)
            $luigis->categories()->attach($pizzasCategory->Id);
        }
        if ($luigis && $italianCategory) {
            $luigis->categories()->attach($italianCategory->Id);
        }
        if ($sushiExpress && $sushiCategory) {
            $sushiExpress->categories()->attach($sushiCategory->Id);
        }

        // You can also attach multiple categories at once:
        // if ($luigis && $pizzasCategory && $italianCategory) {
        //     $luigis->categories()->attach([$pizzasCategory->Id, $italianCategory->Id]);
        // }

        // Option 2: Direct DB insert into pivot table (less common for seeding relationships)
        // if ($luigis && $pizzasCategory) {
        //     DB::table('RestaurantCategories')->insert([
        //         'RestaurantId' => $luigis->Id,
        //         'CategoryId' => $pizzasCategory->Id,
        //         // 'created_at' => now(), // If pivot table has timestamps
        //         // 'updated_at' => now(),
        //     ]);
        // }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Product;
// use App\Models\Restaurant; // If you also want to seed restaurant reviews

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::where('email', 'user@example.com')->first();
        $adminUser = User::where('email', 'admin@example.com')->first();

        $product1 = Product::where('Name', 'Margherita Pizza')->first();
        $product2 = Product::where('Name', 'Pepperoni Passion')->first();
        // $restaurant1 = Restaurant::where('Name', 'Luigi\'s Pizza Place')->first();

        if (!$user1 || !$adminUser || !$product1) {
            $this->command->warn('Required users or products not found for ReviewSeeder. Skipping some reviews.');
        }

        if ($user1 && $product1) {
            Review::updateOrCreate(
                ['UserId' => $user1->id, 'ProductId' => $product1->Id], // Composite key to find/update
                [
                    'Rating' => 5,
                    'Comment' => 'The Margherita was amazing! Best in town.',
                    'ReviewDate' => now()->subDays(rand(1, 5)),
                ]
            );
        }

        if ($adminUser && $product1) {
            Review::updateOrCreate(
                ['UserId' => $adminUser->id, 'ProductId' => $product1->Id],
                [
                    'Rating' => 4,
                    'Comment' => 'Pretty good, a bit greasy but tasty.',
                    'ReviewDate' => now()->subDays(rand(6, 10)),
                ]
            );
        }

        if ($user1 && $product2) {
            Review::updateOrCreate(
                ['UserId' => $user1->id, 'ProductId' => $product2->Id],
                [
                    'Rating' => 4,
                    'Comment' => 'Pepperoni Passion was good, very spicy!',
                    'ReviewDate' => now()->subDays(rand(1, 5)),
                ]
            );
        }

        // Example for a restaurant review (if you implement that functionality)
        // if ($user1 && $restaurant1) {
        //     Review::updateOrCreate(
        //         ['UserId' => $user1->id, 'RestaurantId' => $restaurant1->Id], // Assuming you add RestaurantId to Review model/table
        //         [
        //             'Rating' => 5,
        //             'Comment' => 'Great atmosphere at Luigi\'s!',
        //             'ReviewDate' => now()->subDays(rand(1,10)),
        //         ]
        //     );
        // }

        $this->command->info('Reviews seeded.');
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Product;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $user1 = User::where('email', 'user@example.com')->first();
        $adminUser = User::where('email', 'admin@example.com')->first();

        $product1 = Product::where('Name', 'Margherita Pizza')->first();
        $product2 = Product::where('Name', 'Pepperoni Passion')->first();

        if (!$user1 || !$adminUser || !$product1) {
            $this->command->warn('Required users or products not found for ReviewSeeder. Skipping some reviews.');
        }

        if ($user1 && $product1) {
            Review::updateOrCreate(
                ['UserId' => $user1->id, 'ProductId' => $product1->Id],
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


        $this->command->info('Reviews seeded.');
    }
}
<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $user1 = User::where('email', 'user@example.com')->first();
        $product1 = Product::where('Name', 'Margherita Pizza')->first();
        $product2 = Product::where('Name', 'Pepperoni Passion')->first();

        if ($user1 && $product1) {
            $user1->favoriteProducts()->attach($product1->Id, ['DateAdded' => now()]);
            $this->command->info("Favorited '{$product1->Name}' for '{$user1->email}'.");
        } else {
            if (!$user1) {
                $this->command->warn("User user@example.com not found for FavoriteSeeder.");
            }
            if (!$product1) {
                $this->command->warn("Product 'Margherita' not found for FavoriteSeeder.");
            }
        }

        if ($user1 && $product2) {
            $user1->favoriteProducts()->attach($product2->Id);
            $this->command->info("Favorited '{$product2->Name}' for '{$user1->email}' (using DB default DateAdded).");
        } else {
            if (!$user1) {
                $this->command->warn("User user@example.com not found for FavoriteSeeder.");
            }
            if (!$product2) {
                $this->command->warn("Product 'Diavola' not found for FavoriteSeeder.");
            }
        }
    }
}
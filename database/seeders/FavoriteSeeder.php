<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
// No need to use App\Models\Favorite directly here if using attach()

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $user1 = User::where('email', 'user@example.com')->first();
        // Ensure ProductSeeder has run and created these products
        $product1 = Product::where('Name', 'Margherita Pizza')->first();
        $product2 = Product::where('Name', 'Pepperoni Passion')->first();

        if ($user1 && $product1) {
            // Attach product1 to user1's favorites, explicitly setting DateAdded
            $user1->favoriteProducts()->attach($product1->Id, ['DateAdded' => now()]);
            $this->command->info("Favorited '{$product1->Name}' for '{$user1->email}'.");
        } else {
            if(!$user1) $this->command->warn("User user@example.com not found for FavoriteSeeder.");
            if(!$product1) $this->command->warn("Product 'Margherita Pizza' not found for FavoriteSeeder.");
        }

        if ($user1 && $product2) {
            // Attach product2, let DateAdded use its database default (if migration has useCurrent())
            // If DateAdded is fillable in Favorite model, and migration doesn't have useCurrent(),
            // you might need to provide it or it will be null.
            // Since our migration for Favorites has ->timestamp('DateAdded')->useCurrent();
            // omitting it here should be fine.
            $user1->favoriteProducts()->attach($product2->Id);
            $this->command->info("Favorited '{$product2->Name}' for '{$user1->email}' (using DB default DateAdded).");
        } else {
            if(!$user1) $this->command->warn("User user@example.com not found for FavoriteSeeder.");
            if(!$product2) $this->command->warn("Product 'Pepperoni Passion' not found for FavoriteSeeder.");
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Restaurant;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // This seeder now ONLY creates data. Cleanup is handled in DatabaseSeeder.

        $categories = Category::all()->keyBy('Name');
        $restaurants = Restaurant::all()->keyBy('Name');

        $products = [
            [
                'Restaurant' => 'Pizzeria Ciao a Tutti', 'Category' => 'Pizzas', 'Name' => 'Margherita',
                'Description' => 'Sos pomidorowy San Marzano, mozzarella fior di latte, parmezan, świeża bazylia, oliwa z oliwek.',
                'Price' => 32.00, 'ImageUrl' => 'https://placehold.co/800x600/E91E63/FFFFFF?text=Margherita'
            ],
            [
                'Restaurant' => 'Pizzeria Ciao a Tutti', 'Category' => 'Pizzas', 'Name' => 'Diavola',
                'Description' => 'Sos pomidorowy, mozzarella fior di latte, pikantne salami Spianata Calabra, papryczka chili.',
                'Price' => 38.00, 'ImageUrl' => 'https://placehold.co/800x600/F44336/FFFFFF?text=Diavola'
            ],
            [
                'Restaurant' => 'Pizzeria Ciao a Tutti', 'Category' => 'Napoje', 'Name' => 'Coca-Cola',
                'Description' => 'Oryginalny smak Coca-Cola.',
                'Price' => 8.00, 'ImageUrl' => 'https://placehold.co/800x600/c10c0c/FFFFFF?text=Cola'
            ],
            [
                'Restaurant' => 'Uki Uki', 'Category' => 'Zupy', 'Name' => 'Tantan Udon',
                'Description' => 'Aromatyczny bulion z pastą sezamową i sosem sojowym z mielonym mięsem wieprzowym.',
                'Price' => 45.00, 'ImageUrl' => 'https://placehold.co/800x600/4A148C/FFFFFF?text=Tantan+Udon'
            ],
            [
                'Restaurant' => 'Uki Uki', 'Category' => 'Zupy', 'Name' => 'Kake Udon',
                'Description' => 'Klasyczny bulion rybny na bazie płatków katsuobushi i wodorostów kombu z grubym makaronem udon.',
                'Price' => 35.00, 'ImageUrl' => 'https://placehold.co/800x600/3F51B5/FFFFFF?text=Kake+Udon'
            ],
            [
                'Restaurant' => 'Barn Burger', 'Category' => 'Burgery', 'Name' => 'Classic Barn',
                'Description' => 'Soczysta wołowina 200g, sałata, pomidor, czerwona cebula, ogórek konserwowy, sos BBQ.',
                'Price' => 39.00, 'ImageUrl' => 'https://placehold.co/800x600/795548/FFFFFF?text=Classic+Burger'
            ],
            [
                'Restaurant' => 'Barn Burger', 'Category' => 'Burgery', 'Name' => 'BBQ Bekon',
                'Description' => 'Wołowina 200g, chrupiący bekon, ser cheddar, krążki cebulowe, sałata, sos BBQ.',
                'Price' => 44.00, 'ImageUrl' => 'https://placehold.co/800x600/A1887F/FFFFFF?text=BBQ+Bacon'
            ],
            [
                'Restaurant' => 'Barn Burger', 'Category' => 'Przystawki', 'Name' => 'Frytki Belgijskie',
                'Description' => 'Grubo krojone, podwójnie smażone frytki z ziemniaków.',
                'Price' => 15.00, 'ImageUrl' => 'https://placehold.co/800x600/FFC107/000000?text=Fries'
            ],
            [
                'Restaurant' => 'Vegan Ramen Shop', 'Category' => 'Zupy', 'Name' => 'Spicy Miso Ramen',
                'Description' => 'Kremowy bulion na bazie grzybów i warzyw z pastą miso, olejem chili, tofu i warzywami.',
                'Price' => 42.00, 'ImageUrl' => 'https://placehold.co/800x600/4CAF50/FFFFFF?text=Spicy+Ramen'
            ],
            [
                'Restaurant' => 'Vegan Ramen Shop', 'Category' => 'Zupy', 'Name' => 'Clear Shoyu Ramen',
                'Description' => 'Klarowny bulion warzywny z sosem sojowym, boczniakami, bambusem i nori.',
                'Price' => 39.00, 'ImageUrl' => 'https://placehold.co/800x600/8BC34A/FFFFFF?text=Shoyu+Ramen'
            ],
        ];

        foreach ($products as $productData) {
            $restaurant = $restaurants->get($productData['Restaurant']);
            $category = $categories->get($productData['Category']);

            if ($restaurant && $category) {
                Product::create([
                    'Name' => $productData['Name'],
                    'Description' => $productData['Description'],
                    'Price' => $productData['Price'],
                    'ImageUrl' => $productData['ImageUrl'],
                    'RestaurantId' => $restaurant->Id,
                    'CategoryId' => $category->Id,
                ]);
            }
        }
    }
}
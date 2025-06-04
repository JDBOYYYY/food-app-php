<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        Restaurant::create([
            // 'Id' => 1, // REMOVE THIS LINE
            'Name' => 'Luigi\'s Pizza Place',
            'ImageUrl' => 'http://example.com/luigis.jpg',
            'AverageRating' => 4.5,
            'DeliveryTime' => '30-45 min',
            'Distance' => '2.5 km',
            'PriceRange' => '$$'
        ]);

        Restaurant::create([
            // 'Id' => 2, // REMOVE THIS LINE
            'Name' => 'Sushi Express',
            'ImageUrl' => 'http://example.com/sushi.jpg',
            'AverageRating' => 4.2,
            'DeliveryTime' => '45-60 min',
            'Distance' => '5.1 km',
            'PriceRange' => '$$$'
        ]);
        // Add more if you like, without specifying 'Id'
    }
}
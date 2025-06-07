<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        // The main DatabaseSeeder will handle truncation.
        // This seeder is now only for creating data.

        $restaurants = [
            // Italian & Pizza
            [
                'Name' => 'Pizzeria Ciao a Tutti',
                'ImageUrl' => 'https://images.pexels.com/photos/1146760/pexels-photo-1146760.jpeg?auto=compress&cs=tinysrgb&w=800',
                'AverageRating' => 4.8, 'DeliveryTime' => '25-40 min', 'Distance' => '3.1 km', 'PriceRange' => '$$'
            ],
            [
                'Name' => 'Tutti Santi',
                'ImageUrl' => 'https://images.pexels.com/photos/2147491/pexels-photo-2147491.jpeg?auto=compress&cs=tinysrgb&w=800',
                'AverageRating' => 4.8, 'DeliveryTime' => '35-50 min', 'Distance' => '5.8 km', 'PriceRange' => '$$$'
            ],
            [
                'Name' => 'Superiore',
                'ImageUrl' => 'https://images.pexels.com/photos/1260968/pexels-photo-1260968.jpeg?auto=compress&cs=tinysrgb&w=800',
                'AverageRating' => 4.7, 'DeliveryTime' => '30-45 min', 'Distance' => '4.0 km', 'PriceRange' => '$$$'
            ],
            [
                'Name' => 'Brooklyn Pizza',
                'ImageUrl' => 'https://images.pexels.com/photos/315755/pexels-photo-315755.jpeg?auto=compress&cs=tinysrgb&w=800',
                'AverageRating' => 4.5, 'DeliveryTime' => '25-40 min', 'Distance' => '4.1 km', 'PriceRange' => '$$'
            ],
            // Asian
            [
                'Name' => 'Uki Uki',
                'ImageUrl' => 'https://images.pexels.com/photos/2347311/pexels-photo-2347311.jpeg?auto=compress&cs=tinysrgb&w=800',
                'AverageRating' => 4.9, 'DeliveryTime' => '30-45 min', 'Distance' => '1.5 km', 'PriceRange' => '$$$'
            ],
            [
                'Name' => 'Thaisty',
                'ImageUrl' => 'https://images.pexels.com/photos/2097090/pexels-photo-2097090.jpeg?auto=compress&cs=tinysrgb&w=800',
                'AverageRating' => 4.6, 'DeliveryTime' => '40-55 min', 'Distance' => '5.5 km', 'PriceRange' => '$$$'
            ],
            [
                'Name' => 'San Thai',
                'ImageUrl' => 'https://images.pexels.com/photos/4057737/pexels-photo-4057737.jpeg?auto=compress&cs=tinysrgb&w=800',
                'AverageRating' => 4.7, 'DeliveryTime' => '35-50 min', 'Distance' => '3.5 km', 'PriceRange' => '$$'
            ],
            [
                'Name' => 'Vegan Ramen Shop',
                'ImageUrl' => 'https://images.pexels.com/photos/1907097/pexels-photo-1907097.jpeg?auto=compress&cs=tinysrgb&w=800',
                'AverageRating' => 4.9, 'DeliveryTime' => '30-45 min', 'Distance' => '2.8 km', 'PriceRange' => '$$$'
            ],
            [
                'Name' => 'Sushi Zushi',
                'ImageUrl' => 'https://images.pexels.com/photos/357756/pexels-photo-357756.jpeg?auto=compress&cs=tinysrgb&w=800',
                'AverageRating' => 4.7, 'DeliveryTime' => '40-55 min', 'Distance' => '6.0 km', 'PriceRange' => '$$$'
            ],
            // Burgers & American
            [
                'Name' => 'Barn Burger',
                'ImageUrl' => 'https://images.pexels.com/photos/1639562/pexels-photo-1639562.jpeg?auto=compress&cs=tinysrgb&w=800',
                'AverageRating' => 4.6, 'DeliveryTime' => '35-50 min', 'Distance' => '4.8 km', 'PriceRange' => '$$'
            ],
            [
                'Name' => 'Bobby Burger',
                'ImageUrl' => 'https://images.pexels.com/photos/1556698/pexels-photo-1556698.jpeg?auto=compress&cs=tinysrgb&w=800',
                'AverageRating' => 4.3, 'DeliveryTime' => '20-35 min', 'Distance' => '1.2 km', 'PriceRange' => '$'
            ],
            [
                'Name' => 'Mr. Pancake',
                'ImageUrl' => 'https://images.pexels.com/photos/376464/pexels-photo-376464.jpeg?auto=compress&cs=tinysrgb&w=800',
                'AverageRating' => 4.4, 'DeliveryTime' => '30-45 min', 'Distance' => '3.7 km', 'PriceRange' => '$$'
            ],
            // Korean & Chicken
            [
                'Name' => 'Kura',
                'ImageUrl' => 'https://images.pexels.com/photos/60616/fried-chicken-chicken-fried-crunchy-60616.jpeg?auto=compress&cs=tinysrgb&w=800',
                'AverageRating' => 4.7, 'DeliveryTime' => '20-35 min', 'Distance' => '2.2 km', 'PriceRange' => '$$'
            ],
            [
                'Name' => 'K-bar',
                'ImageUrl' => 'https://images.pexels.com/photos/2474661/pexels-photo-2474661.jpeg?auto=compress&cs=tinysrgb&w=800',
                'AverageRating' => 4.5, 'DeliveryTime' => '20-35 min', 'Distance' => '1.9 km', 'PriceRange' => '$'
            ],
            // Middle Eastern & Vegan
            [
                'Name' => 'Raj w Niebie',
                'ImageUrl' => 'https://images.pexels.com/photos/1640777/pexels-photo-1640777.jpeg?auto=compress&cs=tinysrgb&w=800',
                'AverageRating' => 4.7, 'DeliveryTime' => '30-45 min', 'Distance' => '2.9 km', 'PriceRange' => '$$'
            ],
            [
                'Name' => 'Tel Aviv Urban Food',
                'ImageUrl' => 'https://images.pexels.com/photos/4099239/pexels-photo-4099239.jpeg?auto=compress&cs=tinysrgb&w=800',
                'AverageRating' => 4.8, 'DeliveryTime' => '25-40 min', 'Distance' => '1.8 km', 'PriceRange' => '$$$'
            ],
            // Mexican
            [
                'Name' => 'La Sirena: The Mexican Food Cartel',
                'ImageUrl' => 'https://images.pexels.com/photos/461198/pexels-photo-461198.jpeg?auto=compress&cs=tinysrgb&w=800',
                'AverageRating' => 4.6, 'DeliveryTime' => '25-40 min', 'Distance' => '3.3 km', 'PriceRange' => '$$'
            ],
            // Seafood
            [
                'Name' => 'Shrimp House',
                'ImageUrl' => 'https://images.pexels.com/photos/1300972/pexels-photo-1300972.jpeg?auto=compress&cs=tinysrgb&w=800',
                'AverageRating' => 4.6, 'DeliveryTime' => '25-40 min', 'Distance' => '2.1 km', 'PriceRange' => '$$'
            ],
            // Polish & European
            [
                'Name' => 'AiOLI Cantine Bar Cafe Deli',
                'ImageUrl' => 'https://images.pexels.com/photos/1437267/pexels-photo-1437267.jpeg?auto=compress&cs=tinysrgb&w=800',
                'AverageRating' => 4.4, 'DeliveryTime' => '30-45 min', 'Distance' => '0.8 km', 'PriceRange' => '$$'
            ],
            [
                'Name' => 'Nice To See You',
                'ImageUrl' => 'https://images.pexels.com/photos/1099680/pexels-photo-1099680.jpeg?auto=compress&cs=tinysrgb&w=800',
                'AverageRating' => 4.8, 'DeliveryTime' => '15-30 min', 'Distance' => '0.5 km', 'PriceRange' => '$$'
            ],
            [
                'Name' => 'Cuda na Kiju',
                'ImageUrl' => 'https://images.pexels.com/photos/67468/pexels-photo-67468.jpeg?auto=compress&cs=tinysrgb&w=800',
                'AverageRating' => 4.5, 'DeliveryTime' => '25-40 min', 'Distance' => '0.9 km', 'PriceRange' => '$$'
            ],
            [
                'Name' => 'RASOI Indian Restaurant',
                'ImageUrl' => 'https://images.pexels.com/photos/958545/pexels-photo-958545.jpeg?auto=compress&cs=tinysrgb&w=800',
                'AverageRating' => 4.8,
                'DeliveryTime' => '45-60 min',
                'Distance' => '6.2 km',
                'PriceRange' => '$$'
            ],
        ];

        foreach ($restaurants as $restaurantData) {
            Restaurant::create($restaurantData);
        }
    }
}
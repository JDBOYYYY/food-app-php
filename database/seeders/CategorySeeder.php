<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $categories = [
            ['Name' => 'Śniadania'],
            ['Name' => 'Kanapki'],
            ['Name' => 'Przystawki'],
            ['Name' => 'Tortilla'],
            ['Name' => 'Burgery'],
            ['Name' => 'Pizzas'],
            ['Name' => 'Sałatki'],
            ['Name' => 'Zupy'],
            ['Name' => 'Dania główne'],
            ['Name' => 'Desery'],
            ['Name' => 'Napoje'],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }
        /*
        DB::table('Categories')->insert([
            ['Id' => 1, 'Name' => 'Śniadania', 'created_at' => now(), 'updated_at' => now()],
            ['Id' => 2, 'Name' => 'Kanapki', 'created_at' => now(), 'updated_at' => now()],
            ['Id' => 3, 'Name' => 'Przystawki', 'created_at' => now(), 'updated_at' => now()],
            ['Id' => 4, 'Name' => 'Tortilla', 'created_at' => now(), 'updated_at' => now()],
            ['Id' => 5, 'Name' => 'Burgery', 'created_at' => now(), 'updated_at' => now()],
        ]);
        */
    }
}

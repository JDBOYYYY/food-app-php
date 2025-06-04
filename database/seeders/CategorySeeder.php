<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents; // Usually not needed for simple seeders
use Illuminate\Database\Seeder;
use App\Models\Category; // Import the Category model
use Illuminate\Support\Facades\DB; // Useful for raw inserts or managing constraints

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data from your init.sql:
        // INSERT INTO Categories (Id, Name) VALUES
        // (1, N'Śniadania'), (2, N'Kanapki'), (3, N'Przystawki'),
        // (4, N'Tortilla'), (5, N'Burgery');

        // Option 1: Using Eloquent Model (Category::create)
        // This is generally preferred if your 'Id' column is auto-incrementing
        // and you don't need to force specific IDs from the old init.sql.
        // If 'Id' is NOT auto-incrementing OR if 'Id' is NOT in $fillable in Category.php,
        // this approach might need adjustment or you'd use Option 2.

        // For this example, let's assume 'Id' is auto-incrementing and we don't need to force old IDs.
        // If you *do* need to force IDs, see Option 2 or ensure 'Id' is fillable.

        $categories = [
            ['Name' => 'Śniadania'],  // Breakfasts
            ['Name' => 'Kanapki'],    // Sandwiches
            ['Name' => 'Przystawki'], // Appetizers
            ['Name' => 'Tortilla'],   // Tortillas/Wraps
            ['Name' => 'Burgery'],    // Burgers
            // Add any other default categories you had
            ['Name' => 'Pizzas'],     // Example, if you used this in testing
            ['Name' => 'Sałatki'],    // Salads
            ['Name' => 'Zupy'],       // Soups
            ['Name' => 'Dania główne'],// Main Courses
            ['Name' => 'Desery'],     // Desserts
            ['Name' => 'Napoje'],     // Drinks
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Option 2: Using DB Facade to insert with specific IDs (if needed from init.sql)
        // This bypasses Eloquent's $fillable and auto-increment behavior for 'Id'.
        // Useful if you need to maintain the exact IDs from your old system for some reason.
        /*
        DB::table('Categories')->insert([
            ['Id' => 1, 'Name' => 'Śniadania', 'created_at' => now(), 'updated_at' => now()],
            ['Id' => 2, 'Name' => 'Kanapki', 'created_at' => now(), 'updated_at' => now()],
            ['Id' => 3, 'Name' => 'Przystawki', 'created_at' => now(), 'updated_at' => now()],
            ['Id' => 4, 'Name' => 'Tortilla', 'created_at' => now(), 'updated_at' => now()],
            ['Id' => 5, 'Name' => 'Burgery', 'created_at' => now(), 'updated_at' => now()],
            // Add others if you are using specific IDs
            // ['Id' => 6, 'Name' => 'Pizzas', 'created_at' => now(), 'updated_at' => now()],
        ]);
        */

        // If you use DB::table()->insert() and your table has auto-incrementing IDs,
        // you might need to reset the sequence after inserting with explicit IDs,
        // though for SQL Server, IDENTITY columns usually handle this okay.
        // For PostgreSQL, you'd do: DB::select("SELECT setval(pg_get_serial_sequence('Categories', 'Id'), coalesce(max(Id), 0)+1, false) FROM Categories;");
        // For SQL Server, if you insert with IDENTITY_INSERT ON, it's usually fine.
    }
}

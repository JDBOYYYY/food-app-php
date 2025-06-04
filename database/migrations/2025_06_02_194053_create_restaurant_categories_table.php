<?php
// In ..._create_restaurant_categories_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('RestaurantCategories', function (Blueprint $table) { // Match C# table name
            $table->unsignedBigInteger('RestaurantId');
            $table->foreign('RestaurantId', 'FK_RestaurantCategories_Restaurants') // Optional: name constraint
                  ->references('Id')->on('Restaurants')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('CategoryId');
            $table->foreign('CategoryId', 'FK_RestaurantCategories_Categories') // Optional: name constraint
                  ->references('Id')->on('Categories')
                  ->onDelete('cascade');

            // Composite primary key
            $table->primary(['RestaurantId', 'CategoryId']);

            // If this pivot table had extra attributes (e.g., display_order), you'd add them here.
            // $table->integer('display_order')->default(0);

            // Pivot tables usually don't need their own timestamps unless you specifically want to track
            // when a restaurant was associated/disassociated with a category.
            // If you want them:
            // $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('RestaurantCategories');
    }
};
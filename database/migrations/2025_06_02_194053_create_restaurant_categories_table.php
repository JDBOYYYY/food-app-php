<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('RestaurantCategories', function (Blueprint $table) {
            $table->unsignedBigInteger('RestaurantId');
            $table->foreign('RestaurantId', 'FK_RestaurantCategories_Restaurants')
                  ->references('Id')->on('Restaurants')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('CategoryId');
            $table->foreign('CategoryId', 'FK_RestaurantCategories_Categories')
                  ->references('Id')->on('Categories')
                  ->onDelete('cascade');
            $table->primary(['RestaurantId', 'CategoryId']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('RestaurantCategories');
    }
};
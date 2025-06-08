<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Restaurants', function (Blueprint $table) {
            $table->id('Id');
            $table->string('Name', 150);
            $table->string('ImageUrl', 1024)->nullable();
            $table->float('AverageRating')->nullable();
            $table->string('DeliveryTime', 50)->nullable();
            $table->string('Distance', 50)->nullable();
            $table->string('PriceRange', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Restaurants');
    }
};

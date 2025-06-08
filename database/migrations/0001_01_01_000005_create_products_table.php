<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Products', function (Blueprint $table) {
            $table->id('Id');
            $table->string('Name', 100);
            $table->text('Description')->nullable();
            $table->decimal('Price', 18, 2);
            $table->string('ImageUrl', 1024)->nullable();

            $table->unsignedBigInteger('RestaurantId');
            $table->foreign('RestaurantId', 'FK_Products_Restaurants')
                ->references('Id')->on('Restaurants')
                ->onDelete('cascade');

            $table->unsignedBigInteger('CategoryId');
            $table->foreign('CategoryId', 'FK_Products_Categories')
                ->references('Id')->on('Categories');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Products');
    }
};

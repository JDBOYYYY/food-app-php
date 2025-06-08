<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('OrderItems', function (Blueprint $table) {
            $table->id('Id');

            $table->unsignedBigInteger('OrderId');
            $table->foreign('OrderId', 'FK_OrderItems_Orders')
                  ->references('Id')->on('Orders')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('ProductId');
            $table->foreign('ProductId', 'FK_OrderItems_Products')
                  ->references('Id')->on('Products');

            $table->integer('Quantity');
            $table->decimal('UnitPrice', 18, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('OrderItems');
    }
};
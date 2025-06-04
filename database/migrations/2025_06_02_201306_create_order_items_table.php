<?php
// In ..._create_order_items_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('OrderItems', function (Blueprint $table) { // Match C# table name
            $table->id('Id');

            $table->unsignedBigInteger('OrderId');
            $table->foreign('OrderId', 'FK_OrderItems_Orders')
                  ->references('Id')->on('Orders')
                  ->onDelete('cascade'); // If order is deleted, its items are deleted

            $table->unsignedBigInteger('ProductId');
            $table->foreign('ProductId', 'FK_OrderItems_Products')
                  ->references('Id')->on('Products');
                //   ->onDelete('restrict'); // Prevent product deletion if in an order item
                                          // Or ->nullable()->constrained()->nullOnDelete();

            $table->integer('Quantity');
            $table->decimal('UnitPrice', 18, 2); // Price at the time of order

            $table->timestamps(); // Optional, but good for tracking item changes if any
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('OrderItems');
    }
};
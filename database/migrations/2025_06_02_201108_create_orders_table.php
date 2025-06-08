<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Orders', function (Blueprint $table) {
            $table->id('Id');

            $table->unsignedBigInteger('UserId');
            $table->foreign('UserId', 'FK_Orders_Users')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->timestamp('OrderDate')->useCurrent();
            $table->decimal('TotalAmount', 18, 2);
            $table->string('Status', 50);

            $table->unsignedBigInteger('ShippingAddressId')->nullable();
            $table->foreign('ShippingAddressId', 'FK_Orders_ShippingAddress')
                  ->references('Id')->on('Addresses');

            $table->unsignedBigInteger('BillingAddressId')->nullable();
            $table->foreign('BillingAddressId', 'FK_Orders_BillingAddress')
                  ->references('Id')->on('Addresses');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Orders');
    }
};
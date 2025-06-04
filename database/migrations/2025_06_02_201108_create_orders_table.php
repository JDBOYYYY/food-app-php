<?php
// In ..._create_orders_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Orders', function (Blueprint $table) { // Match C# table name
            $table->id('Id'); // INT IDENTITY(1,1) PRIMARY KEY

            $table->unsignedBigInteger('UserId');
            $table->foreign('UserId', 'FK_Orders_Users')
                  ->references('id')->on('users') // References users.id
                  ->onDelete('cascade'); // Or restrict/set null depending on business logic

            $table->timestamp('OrderDate')->useCurrent(); // DATETIME NOT NULL DEFAULT GETDATE()
            $table->decimal('TotalAmount', 18, 2);
            $table->string('Status', 50); // e.g., 'Pending', 'Processing', 'Completed', 'Cancelled'

            $table->unsignedBigInteger('ShippingAddressId')->nullable();
            $table->foreign('ShippingAddressId', 'FK_Orders_ShippingAddress')
                  ->references('Id')->on('Addresses'); // References Addresses.Id
                //   ->onDelete('set null'); // If address is deleted, don't delete order, just nullify link

            $table->unsignedBigInteger('BillingAddressId')->nullable();
            $table->foreign('BillingAddressId', 'FK_Orders_BillingAddress')
                  ->references('Id')->on('Addresses'); // References Addresses.Id
                //   ->onDelete('set null');

            // Add any other order-specific fields like notes, payment_method_id (later) etc.
            // $table->text('Notes')->nullable();

            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Orders');
    }
};
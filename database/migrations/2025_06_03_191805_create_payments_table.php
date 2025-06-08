<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Payments', function (Blueprint $table) {
            $table->id('Id');

            $table->unsignedBigInteger('OrderId');
            $table->foreign('OrderId', 'FK_Payments_Orders')
                  ->references('Id')->on('Orders')
                  ->onDelete('cascade');

            $table->timestamp('PaymentDate')->useCurrent();
            $table->decimal('Amount', 18, 2);
            $table->string('PaymentMethod', 50);
            $table->string('TransactionId', 100)->nullable();
            $table->string('Status', 50);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Payments');
    }
};
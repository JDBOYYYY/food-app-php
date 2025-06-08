<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Addresses', function (Blueprint $table) {
            $table->id('Id');
            $table->string('Street', 100);
            $table->string('Apartment')->nullable();
            $table->string('City', 50);
            $table->string('PostalCode', 20);
            $table->string('Country', 50);
            $table->unsignedBigInteger('UserId');
            $table->foreign('UserId', 'FK_Addresses_Users')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Addresses');
    }
};
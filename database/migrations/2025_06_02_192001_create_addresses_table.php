<?php
// In ..._create_addresses_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Addresses', function (Blueprint $table) { // Your table name 'Addresses' is fine
            $table->id('Id'); // Your primary key 'Id' for this table is fine
            $table->string('Street', 100);
            $table->string('Apartment')->nullable();
            $table->string('City', 50);
            $table->string('PostalCode', 20);
            $table->string('Country', 50);

            // Foreign key to Laravel's 'users' table
            $table->unsignedBigInteger('UserId'); // This column name on 'Addresses' table is fine
            $table->foreign('UserId', 'FK_Addresses_Users') // Optional: name constraint
                  ->references('id')->on('users')         // CORRECTED: references 'id' PK on 'users' table
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Addresses');
    }
};
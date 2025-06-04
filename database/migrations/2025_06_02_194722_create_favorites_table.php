<?php
// In ..._create_favorites_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Favorites', function (Blueprint $table) { // Match C# table name
            $table->unsignedBigInteger('UserId'); // Foreign key to users table
            $table->foreign('UserId', 'FK_Favorites_Users') // Optional: name constraint
                  ->references('id')->on('users') // Assumes users.id
                  ->onDelete('cascade');

            $table->unsignedBigInteger('ProductId'); // Foreign key to Products table
            $table->foreign('ProductId', 'FK_Favorites_Products') // Optional: name constraint
                  ->references('Id')->on('Products') // Assumes Products.Id
                  ->onDelete('cascade');

            $table->timestamp('DateAdded')->useCurrent(); // DATETIME NOT NULL DEFAULT GETDATE()

            // Composite primary key
            $table->primary(['UserId', 'ProductId']);

            // Note: Laravel's convention for pivot tables with models is to also have an $table->id();
            // if you want to treat it like a regular model with its own ID,
            // in addition to the composite key for uniqueness.
            // For this direct translation, we'll stick to the composite PK.
            // If you added $table->id(); you'd remove $table->primary([...]).
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Favorites');
    }
};
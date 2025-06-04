<?php
// In ..._create_reviews_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // For raw SQL for CHECK constraint

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Reviews', function (Blueprint $table) { // Match C# table name
            $table->id('Id');

            $table->unsignedBigInteger('UserId');
            $table->foreign('UserId', 'FK_Reviews_Users')
                  ->references('id')->on('users') // References users.id
                  ->onDelete('cascade');

            $table->unsignedBigInteger('ProductId');
            $table->foreign('ProductId', 'FK_Reviews_Products')
                  ->references('Id')->on('Products') // References Products.Id
                  ->onDelete('cascade');

            $table->integer('Rating'); // Or $table->tinyInteger('Rating')->unsigned(); for 1-5
            $table->text('Comment')->nullable(); // NVARCHAR(MAX)
            $table->timestamp('ReviewDate')->useCurrent(); // DATETIME NOT NULL DEFAULT GETDATE()

            $table->timestamps(); // created_at, updated_at

            // Laravel's schema builder doesn't directly support CHECK constraints
            // in a database-agnostic way. We can add it with raw SQL for SQL Server.
        });

        // Add CHECK constraint using raw SQL (SQL Server specific)
        // Ensure this runs after the table is created.
        if (config('database.default') === 'sqlsrv') {
            DB::statement('ALTER TABLE Reviews ADD CONSTRAINT CK_Review_Rating CHECK (Rating >= 1 AND Rating <= 5)');
        }
    }

    public function down(): void
    {
        // If you added a raw constraint, you might need to drop it rawly too,
        // though dropping the table usually handles it.
        // For robustness, you could drop the constraint first if it exists.
        // if (config('database.default') === 'sqlsrv') {
        //     DB::statement('ALTER TABLE Reviews DROP CONSTRAINT IF EXISTS CK_Review_Rating');
        // }
        Schema::dropIfExists('Reviews');
    }
};
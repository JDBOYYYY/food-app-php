<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Reviews', function (Blueprint $table) {
            $table->id('Id');

            $table->unsignedBigInteger('UserId');
            $table->foreign('UserId', 'FK_Reviews_Users')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('ProductId');
            $table->foreign('ProductId', 'FK_Reviews_Products')
                  ->references('Id')->on('Products')
                  ->onDelete('cascade');

            $table->integer('Rating');
            $table->text('Comment')->nullable();
            $table->timestamp('ReviewDate')->useCurrent();

            $table->timestamps();
        });
        if (config('database.default') === 'sqlsrv') {
            DB::statement('ALTER TABLE Reviews ADD CONSTRAINT CK_Review_Rating CHECK (Rating >= 1 AND Rating <= 5)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('Reviews');
    }
};
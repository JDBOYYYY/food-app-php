<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('Categories', function (Blueprint $table) { // Match your C# table name
            $table->id('Id'); // Corresponds to INT IDENTITY(1,1) PRIMARY KEY
            $table->string('Name', 50)->unique(); // Corresponds to NVARCHAR(50) NOT NULL UNIQUE
            // Laravel's $table->timestamps(); adds created_at and updated_at,
            // Your .NET model doesn't have these explicitly, but they are good practice.
            // If you don't want them, comment out the line below.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Categories'); // Match your C# table name
    }
};

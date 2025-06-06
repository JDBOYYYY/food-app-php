<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('Addresses', function (Blueprint $table) {
            if (!Schema::hasColumn('Addresses', 'Apartment')) {
                $table->string('Apartment')->nullable()->after('Street');
            }
        });
    }

    public function down(): void
    {
        Schema::table('Addresses', function (Blueprint $table) {
            if (Schema::hasColumn('Addresses', 'Apartment')) {
                $table->dropColumn('Apartment');
            }
        });
    }
};


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
        Schema::table('animals', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('name'); // ou após outro campo relevante
        });

        Schema::table('events', function (Blueprint $table) {
            $table->string('banner')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->dropColumn('photo');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('banner');
        });
    }
};

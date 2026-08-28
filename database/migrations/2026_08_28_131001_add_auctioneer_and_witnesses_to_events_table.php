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
        Schema::table('events', function (Blueprint $table) {
            $table->string('auctioneer')->nullable()->after('name');
            $table->string('witness_1_name')->nullable()->after('auctioneer');
            $table->string('witness_2_name')->nullable()->after('witness_1_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'auctioneer',
                'witness_1_name',
                'witness_2_name',
            ]);
        });
    }
};
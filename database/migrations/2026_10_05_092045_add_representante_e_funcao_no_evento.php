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
            $table->string('representative_name')->nullable()->after('auctioneer');
            $table->string('representative_role')->nullable()->after('representative_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'representative_name',
                'representative_role',
            ]);
        });
    }
};

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
        Schema::table('buyer_parcels', function (Blueprint $table) {
            $table->boolean('invoice_generated')->after('paid')->comment('Boleto Emitido')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buyer_parcels', function (Blueprint $table) {
            $table->dropColumn('invoice_generated');
        });
    }
};

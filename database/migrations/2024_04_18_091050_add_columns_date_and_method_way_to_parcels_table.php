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
        Schema::table('parcels', function (Blueprint $table) {
            $table->foreignId('payment_method_id')->after('order_id')->nullable()->constrained('payment_methods')->cascadeOnDelete();
            $table->date('payment_date')->after('date')->nullable()->comment('Data do pagamento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parcels', function (Blueprint $table) {
            $table->dropColumn('payment_method_id');
            $table->dropColumn('payment_date');
        });
    }
};

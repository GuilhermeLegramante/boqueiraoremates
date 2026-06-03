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
        // Adiciona o campo sale_type na tabela orders
        Schema::table('orders', function (Blueprint $table) {
            $table->string('sale_type')->nullable()->after('payment_way_id');
            $table->decimal('sale_type_percentage', 5, 2)->nullable();
            $table->integer('sale_type_quantity')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove o campo sale_type da tabela orders
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('sale_type');
        });
    }
};

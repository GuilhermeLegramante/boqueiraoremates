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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('output_sending_docs_method_id')
                ->after('output_seller_sending_documentation_way')
                ->nullable()
                ->constrained('sending_docs_methods')
                ->comment('Forma de envio da documentação (saída)')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_output_sending_docs_method_id_foreign');
            $table->dropColumn('output_sending_docs_method_id');
        });
    }
};

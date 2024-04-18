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
            $table->foreignId('entry_sending_docs_method_id')
                ->after('entry_buyer_sending_documentation_way')
                ->nullable()
                ->constrained('sending_docs_methods')
                ->comment('Forma de envio da documentação (entrada)')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_entry_sending_docs_method_id_foreign');
            $table->dropColumn('entry_sending_docs_method_id');
        });
    }
};

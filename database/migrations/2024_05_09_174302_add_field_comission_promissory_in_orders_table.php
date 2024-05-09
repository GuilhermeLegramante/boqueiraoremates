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
            $table->boolean('entry_comission_promissory')->after('entry_promissory')->nullable()->default(0)->comment('Nota promissória da comissão (entrada)');
            $table->boolean('output_comission_promissory')->after('output_promissory')->nullable()->default(0)->comment('Nota promissória da comissão (saída)');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('entry_comission_promissory');
            $table->dropColumn('output_comission_promissory');
        });
    }
};

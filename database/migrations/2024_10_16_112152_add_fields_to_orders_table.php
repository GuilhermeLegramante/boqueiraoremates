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
            $table->boolean('able_to_exam')->nullable()->default(0)->comment('Liberado para exame');
            $table->date('able_to_exam_date')->nullable()->comment('Data da liberação para exame');
            $table->boolean('able_to_loading')->nullable()->default(0)->comment('Liberado para carregamento');
            $table->date('able_to_loading_date')->nullable()->comment('Data da liberação para carregamento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('able_to_exam');
            $table->dropColumn('able_to_exam_date');
            $table->dropColumn('able_to_loading');
            $table->dropColumn('able_to_loading_date');
        });
    }
};

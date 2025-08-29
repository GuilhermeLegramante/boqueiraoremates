<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Alterar start_date e finish_date de date para datetime
            $table->dateTime('start_date')->change();
            $table->dateTime('finish_date')->change();

            // Adicionar pre_start_date e pre_finish_date
            $table->dateTime('pre_start_date')->nullable()->after('finish_date');
            $table->dateTime('pre_finish_date')->nullable()->after('pre_start_date');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Reverter start_date e finish_date para date
            $table->date('start_date')->change();
            $table->date('finish_date')->change();

            // Remover pre_start_date e pre_finish_date
            $table->dropColumn(['pre_start_date', 'pre_finish_date']);
        });
    }
};

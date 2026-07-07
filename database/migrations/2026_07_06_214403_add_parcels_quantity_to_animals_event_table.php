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
        Schema::table('animal_event', function (Blueprint $table) {
            $table->unsignedInteger('parcels_quantity')
                ->nullable()
                ->after('linked_animal_event_id'); // ajuste a coluna de referência se necessário
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animal_event', function (Blueprint $table) {
            $table->dropColumn('parcels_quantity');
        });
    }
};

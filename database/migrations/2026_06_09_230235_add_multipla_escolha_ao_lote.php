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
            $table->foreignId('linked_animal_event_id')
                ->nullable()
                ->constrained('animal_event')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animal_event', function (Blueprint $table) {
            $table->dropForeign(['linked_animal_event_id']);
            $table->dropColumn('linked_animal_event_id');
        });
    }
};

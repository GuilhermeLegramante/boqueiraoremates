<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('animal_event', function (Blueprint $table) {
            // novos campos
            $table->decimal('min_value', 12, 2)->nullable()->after('animal_id');
            $table->decimal('final_value', 12, 2)->nullable()->after('min_value');
            $table->decimal('increment_value', 12, 2)->nullable()->after('final_value');
            $table->decimal('target_value', 12, 2)->nullable()->after('increment_value');
            $table->string('lot_number')->nullable()->after('target_value');
            $table->enum('status', ['disponivel', 'vendido', 'reservado'])
                ->default('disponivel')
                ->after('lot_number');
        });
    }

    public function down(): void
    {
        Schema::table('animal_event', function (Blueprint $table) {
            $table->dropColumn(['min_value', 'final_value', 'increment_value', 'target_value', 'lot_number', 'status']);
        });
    }
};

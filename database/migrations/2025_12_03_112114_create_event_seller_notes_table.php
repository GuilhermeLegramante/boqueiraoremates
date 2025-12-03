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
        Schema::create('event_seller_notes', function (Blueprint $table) {
            $table->id();
            $table->text('note')->nullable();

            // Relação com events
            $table->foreignId('event_id')
                ->constrained('events')
                ->onDelete('cascade');

            // Relação com clients (seller_id)
            $table->foreignId('seller_id')
                ->constrained('clients')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_seller_notes');
    }
};

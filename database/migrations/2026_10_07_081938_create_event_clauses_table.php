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
        Schema::create('event_clauses', function (Blueprint $table) {
            $table->id();

            // Chave estrangeira para o evento
            $table->foreignId('event_id')
                ->constrained('events')
                ->cascadeOnDelete();

            $table->string('title')->nullable(); // Ex: "CLÁUSULA PRIMEIRA" ou "Da Inadimplência"
            $table->longText('content');         // O parágrafo / texto da cláusula
            $table->integer('order')->default(0); // Para controle da sequência/ordem no documento

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_clauses');
    }
};

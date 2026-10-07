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
        Schema::create('event_regulation_clauses', function (Blueprint $table) {
            $table->id();

            // Chave estrangeira para o evento
            $table->foreignId('event_id')
                ->constrained('events')
                ->cascadeOnDelete();

            $table->string('title')->nullable();  // Ex: "ARTIGO 1º" ou "Das Condições de Pagamento"
            $table->longText('content');          // O texto / parágrafo do regulamento
            $table->integer('order')->default(0);  // Ordem de exibição no documento

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_regulation_clauses');
    }
};

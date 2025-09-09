<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();

            // Referência à tabela pivot animal_event
            $table->unsignedBigInteger('animal_event_id');

            // Cliente que envia o lance
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Evento
            $table->foreignId('event_id')
                ->constrained('events')
                ->onDelete('cascade');

            $table->decimal('amount', 10, 2);
            $table->integer('status')->default(0); // 0 = pendente, 1 = aprovado, 2 = rejeitado

            // Usuário que aprovou
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            // Chave estrangeira manual para pivot
            $table->foreign('animal_event_id')
                ->references('id')
                ->on('animal_event')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};

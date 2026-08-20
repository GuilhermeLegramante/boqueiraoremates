<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();

            // OS que originou o contrato
            $table->foreignId('order_id')
                ->unique()
                ->constrained('orders')
                ->restrictOnDelete();

            // Usuário que gerou o contrato
            $table->foreignId('generated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // draft | generated | cancelled
            $table->string('status')->default('generated');

            // Versão do modelo do contrato
            $table->string('version')->default('1.0');

            // Dados da OS no momento da emissão
            $table->json('snapshot')->nullable();

            $table->timestamp('generated_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};

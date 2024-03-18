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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('address_id')->nullable()->constrained('addresses')->restrictOnDelete();
            $table->string('email')->nullable();
            $table->string('cpf_cnpj')->nullable();
            $table->string('rg')->nullable();
            $table->string('mother')->nullable(); // nome da mãe
            $table->string('father')->nullable(); // nome do pai
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('business_phone')->nullable();
            $table->string('home_phone')->nullable();
            $table->string('cel_phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->foreignId('bank_id')->nullable()->constrained('banks')->restrictOnDelete();
            $table->string('bank_agency')->nullable();
            $table->string('current_account')->nullable();
            $table->string('occupation')->nullable();
            $table->string('note_occupation')->nullable(); // Obs da Profissão (para autônomos)
            $table->double('income')->nullable(); // Renda
            $table->string('establishment')->nullable(); // Nome do estabelecimento. Ex. Fazenda Alegria
            $table->boolean('has_register_in_another_auctioneer')->nullable()->default(0); // Possui cadastro em outra leiloeira
            $table->string('auctioneer')->nullable(); // Nome da leiloeira
            $table->enum('situation', ['able', 'disabled', 'inactive'])->nullable();
            $table->enum('register_origin', ['marketing', 'local', 'site'])->nullable(); // Canal de inclusão (divulgação, recinto, site)
            $table->enum('profile', ['purchase', 'sale', 'both'])->nullable(); // Perfil (compra, venda ou ambos)





            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};

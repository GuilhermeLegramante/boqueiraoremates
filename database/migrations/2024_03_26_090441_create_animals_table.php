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
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('breed_id')->nullable()->constrained('breeds')->restrictOnDelete(); // raça
            $table->foreignId('animal_type_id')->nullable()->constrained('animal_types')->restrictOnDelete(); 
            $table->foreignId('coat_id')->nullable()->constrained('coats')->restrictOnDelete(); // pêlo
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('register')->nullable();
            $table->string('sbb')->nullable();
            $table->string('rb')->nullable();
            $table->string('mother')->nullable();
            $table->string('father')->nullable();
            $table->enum('breeding', ['breeder', 'whole_male', 'castrated'])->nullable(); // Capacidade de Reprodução: Reprodutor, Macho Inteiro ou Castrado
            $table->enum('blood_level', ['pure', 'mixed'])->nullable(); // Grau de Sangue: Puro ou Mestiço
            $table->double('blood_percentual')->nullable();
            $table->integer('quantity')->nullable(); // Quantidade, para caso de gado pois vende-se o lote
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};

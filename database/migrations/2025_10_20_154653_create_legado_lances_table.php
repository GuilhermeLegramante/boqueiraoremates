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
        Schema::create('legado_lances', function (Blueprint $table) {
            $table->increments('idlance');
            $table->integer('idanimais')->unsigned();
            $table->integer('idcomprador')->unsigned();
            $table->date('datalance');
            $table->time('horalance');
            $table->string('idleilao', 255);
            $table->decimal('lance', 7, 2);
            $table->string('situacao', 255);
            
            // Se quiser adicionar índices ou chaves estrangeiras depois, pode
            // $table->foreign('idanimais')->references('idanimais')->on('legado_dadosanimais');
            // $table->foreign('idcomprador')->references('idclientes')->on('legado_clientes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legado_lances');
    }
};

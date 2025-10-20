<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legado_clientes', function (Blueprint $table) {
            $table->increments('idclientes');
            $table->string('usuario');
            $table->string('senha');
            $table->string('nome');
            $table->string('formachamado');
            $table->string('email');
            $table->string('cpf');
            $table->string('cnpj');
            $table->string('rg');
            $table->string('mae');
            $table->string('pai');
            $table->date('datanasc');
            $table->string('cidade');
            $table->string('estado');
            $table->string('cep');
            $table->string('logradouro');
            $table->string('complemento');
            $table->string('bairro');
            $table->string('pais');
            $table->string('tcomercial');
            $table->string('tresidencial');
            $table->string('tcelular');
            $table->string('tcelular2');
            $table->string('banco');
            $table->string('agencia');
            $table->string('conta');
            $table->string('ativo', 3);
            $table->string('tipo', 2);
            $table->string('profissao');
            $table->string('renda', 20);
            $table->date('datacadastro');
            $table->string('observacao');
        });

        Schema::create('legado_dadosanimais', function (Blueprint $table) {
            $table->increments('idanimais');
            $table->string('idleilao', 11);
            $table->string('especie');
            $table->string('nlote');
            $table->string('nome');
            $table->string('genero');
            $table->string('situacaomacho');
            $table->string('rp');
            $table->date('dtdonascimento');
            $table->string('pelagem');
            $table->string('pai');
            $table->string('mae');
            $table->string('linkqg');
            $table->string('linkfotopq');
            $table->string('linkfotogr');
            $table->string('linkfotocarimbo');
            $table->string('linkv1');
            $table->string('linkv2');
            $table->text('comentarios');
            $table->decimal('lancealvo', 7, 2);
            $table->decimal('implemento', 7, 2);
            $table->decimal('lanceatual', 7, 2);
            $table->decimal('vlrinicial', 7, 2);
            $table->decimal('lanceminimo', 7, 2);
            $table->string('lanceemanalise', 3);
            $table->string('situacaolance');
            $table->integer('idcompradoratual');
            $table->string('ativo', 3);
            $table->string('vendido', 3);
            $table->string('linkregla');
            $table->string('linkfe1');
            $table->string('linkfe2');
            $table->string('linkfe3');
            $table->string('linkfe4');
        });

        Schema::create('legado_leiloes', function (Blueprint $table) {
            $table->increments('idleilao');
            $table->string('nomeleilao');
            $table->text('apresentacao');
            $table->text('descricao');
            $table->date('dataleilao');
            $table->time('horaleilao');
            $table->date('datainicial');
            $table->date('datafinal');
            $table->string('publicado', 3);
            $table->time('horainicial');
            $table->time('horafinal');
            $table->string('condicoes');
            $table->string('linklogo');
            $table->string('linkregremate');
            $table->string('linkregprova');
            $table->string('tipoleilao');
            $table->string('encerrado', 3);
            $table->string('visivel', 3);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legado_clientes');
        Schema::dropIfExists('legado_dadosanimais');
        Schema::dropIfExists('legado_leiloes');
    }
};

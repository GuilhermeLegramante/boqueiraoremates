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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('number')->nullable();
            $table->foreignId('order_status_id')->nullable()->constrained('order_statuses')->restrictOnDelete();
            $table->foreignId('event_id')->nullable()->constrained('events')->restrictOnDelete();
            $table->foreignId('seller_id')->nullable()->constrained('clients')->restrictOnDelete();
            $table->string('service_note')->nullable();
            $table->foreignId('buyer_id')->nullable()->constrained('clients')->restrictOnDelete();
            $table->foreignId('animal_id')->nullable()->constrained('animals')->restrictOnDelete();
            $table->string('batch')->nullable()->comment('Lote');
            $table->double('parcel_value')->nullable()->comment('Valor da Parcela');
            $table->integer('multiplier')->nullable()->comment('Multiplicador');
            $table->double('gross_value')->nullable()->comment('Valor Bruto');
            $table->foreignId('payment_way_id')->nullable()->constrained('payment_ways')->restrictOnDelete()->comment('Fórmula do parcelamento: Ex. 2+2+46');
            $table->double('discount_percentage')->nullable()->comment('Percentual de desconto');
            $table->integer('due_day')->nullable()->comment('Dia do vencimento da parcela');
            $table->integer('reinforcements_amount')->nullable()->comment('Quantidade de reforços');
            $table->double('reinforcement_value')->nullable()->comment('Valor do reforço');
            $table->string('reinforcement_parcels')->nullable()->comment('Parcelas dos reforços (intervalo) Ex. 3-10');
            $table->string('business_note')->nullable()->comment('');
            $table->double('buyer_commission')->nullable()->comment('Percentual de comissão do comprador');
            $table->integer('buyer_commission_installments_number')->nullable()->comment('Quantidade de parcelas'); //
            $table->integer('buyer_due_day')->nullable()->comment('Dia do vencimento da parcela para o faturamento do comprador (comissão)');
            $table->double('seller_commission')->nullable()->comment('Percentual de comissão do vendedor');
            $table->integer('seller_commission_installments_number')->nullable()->comment('Quantidade de parcelas');
            $table->integer('seller_due_day')->nullable()->comment('Dia do vencimento da parcela para o faturamento do comprador (comissão)');
            $table->boolean('entry_contracts')->nullable()->default(0)->comment('Documentação - Entrada (contratos)');
            $table->boolean('entry_promissory')->nullable()->default(0)->comment('Documentação - Entrada (Nota promissória)');
            $table->boolean('entry_register_copy')->nullable()->default(0)->comment('Documentação - Entrada (Cópia do Regulamento');
            $table->enum('entry_first_parcel_business', ['ticket', 'deposit'])->nullable()->comment('Parcela 01 do Negócio (boleto ou depósito)');
            $table->enum('entry_first_parcel_comission', ['ticket', 'deposit'])->nullable()->comment('Parcela 01 da Comissão / Faturamento Boqueirão (boleto ou depósito)');
            $table->date('entry_buyer_sending_documentation_date')->nullable()->comment('Data de envio da documentação ao comprador');
            $table->enum('entry_buyer_sending_documentation_way', ['email', 'whatsapp', 'material'])->nullable()->comment('Forma de envio da documentação ao comprador');
            $table->date('entry_contract_return_date')->nullable()->comment('Data de retorno do contrato (chegada no escritório)');
            $table->string('entry_documentation_note')->nullable()->comment('Observação (Documentação - Entrada)');
            $table->boolean('output_contracts')->nullable()->default(0)->comment('Documentação - Saída (contratos)');
            $table->boolean('output_promissory')->nullable()->default(0)->comment('Documentação - Saída (Nota promissória)');
            $table->boolean('output_register_copy')->nullable()->default(0)->comment('Documentação - Saída (Cópia do Regulamento');
            $table->date('output_first_parcel_date')->nullable()->comment('Data da primeira parcela');
            $table->date('output_sending_documentation_date')->nullable()->comment('Data de envio do processo físico');
            $table->enum('output_seller_sending_documentation_way', ['email', 'whatsapp', 'material'])->nullable()->comment('Forma de envio da documentação ao vendedor');
            $table->string('output_documentation_note')->nullable()->comment('Observação (Documentação - Saída)');
            $table->date('closing_date')->nullable()->comment('Data do encerramento');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

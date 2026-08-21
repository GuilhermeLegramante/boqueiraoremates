<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color', 7)->default('#6B7280');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->foreignId('client_status_id')
                ->nullable()
                ->constrained('client_statuses')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_statuses');

        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['client_status_id']);
            $table->dropColumn('client_status_id');
        });
    }
};

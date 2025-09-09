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
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('published')->default(false)->after('updated_at');
            $table->boolean('closed')->default(false)->after('published');
            $table->boolean('show_lots')->default(true)->after('closed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['published', 'closed', 'show_lots']);
        });
    }
};

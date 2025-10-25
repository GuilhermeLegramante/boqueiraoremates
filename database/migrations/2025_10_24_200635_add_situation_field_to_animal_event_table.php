<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('animal_event', function (Blueprint $table) {
            $table->string('situation')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('animal_event', function (Blueprint $table) {
            $table->dropColumn(['situation']);
        });
    }
};

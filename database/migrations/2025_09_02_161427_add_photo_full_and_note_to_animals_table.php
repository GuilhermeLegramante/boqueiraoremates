<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->string('photo_full')->nullable()->after('photo');
            $table->text('note')->nullable()->after('photo_full');
        });
    }

    public function down(): void
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->dropColumn(['photo_full', 'note']);
        });
    }
};

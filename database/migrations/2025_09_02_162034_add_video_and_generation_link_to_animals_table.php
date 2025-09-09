<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->string('video_link')->nullable()->after('note');
            $table->string('generation_link')->nullable()->after('video_link');
        });
    }

    public function down(): void
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->dropColumn(['video_link', 'generation_link']);
        });
    }
};

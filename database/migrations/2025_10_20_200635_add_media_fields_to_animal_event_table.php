<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('animal_event', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('id');
            $table->string('photo_full')->nullable()->after('photo');
            $table->text('note')->nullable()->after('photo_full');
            $table->string('video_link')->nullable()->after('note');
        });
    }

    public function down(): void
    {
        Schema::table('animal_event', function (Blueprint $table) {
            $table->dropColumn(['photo', 'photo_full', 'note', 'video_link']);
        });
    }
};


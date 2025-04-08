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
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->text('track_uri');
            $table->text('t_time')->nullable();
            $table->text('platform');
            $table->text('ms_played');
            $table->text('track_name');
            $table->text('artist_name');
            $table->text('album_name');
            $table->text('reason_start')->nullable();
            $table->text('reason_end')->nullable();
            $table->text('shuffle')->nullable();
            $table->text('skipped')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};

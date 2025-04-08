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
        Schema::create('weather', function (Blueprint $table) {
            $table->id();
            // $table->string('weather_id');
            $table->string('city_mun_code');
            $table->float('ave_min')->nullable();
            $table->float('ave_max')->nullable();
            $table->float('ave_mean')->nullable();
            $table->text('rainfall_mm')->nullable();
            $table->text('rainfall_description')->nullable();
            $table->text('cloud_cover')->nullable();
            $table->float('humidity')->nullable();;
            $table->text('forecast_date')->nullable();
            $table->text('date_accessed')->nullable();
            $table->string('wind_mps')->nullable();;
            $table->string('direction')->nullable();;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather');
    }
};

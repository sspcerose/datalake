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
        Schema::create('rice_production', function (Blueprint $table) {
            $table->id(); 
            $table->boolean('philippines')->default(false); 
            $table->integer('locType')->nullable(); 
            $table->integer('locCode')->nullable(); 
            $table->integer('provinceCode')->nullable(); 
            $table->year('year')->default(1900)->nullable();
            $table->integer('sem')->nullable();
            $table->float('estProduction')->nullable(); 
            $table->float('areaHav')->nullable(); 
            $table->float('yieldEst')->nullable(); 
            $table->integer('city')->nullable(); 
            $table->integer('eco')->nullable(); 
            $table->integer('province')->nullable(); 
            $table->integer('region')->nullable(); 
            $table->integer('psgc_code')->nullable();
            $table->integer('geoCode')->nullable();
            $table->integer('parent')->nullable();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rice_production');
    }
};

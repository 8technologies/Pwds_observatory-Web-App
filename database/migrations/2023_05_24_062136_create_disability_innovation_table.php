<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisabilityInnovationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disability_innovation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disability_id')->constrained('disabilities')->onDelete('cascade');
            $table->foreignId('innovation_id')->constrained('innovations')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disability_innovation');
    }
}

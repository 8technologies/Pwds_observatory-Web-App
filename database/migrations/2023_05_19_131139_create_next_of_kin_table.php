<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNextOfKinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('next_of_kin', function (Blueprint $table) {
            $table->id();
            $table->string('next_of_kin_last_name')->nullable();
            $table->string('next_of_kin_other_names')->nullable();
            $table->string('next_of_kin_id_number')->nullable();
            $table->enum('next_of_kin_gender',['Male','Female'])->nullable();
            $table->string('next_of_kin_phone_number')->nullable();
            $table->string('next_of_kin_email')->nullable();
            $table->string('next_of_kin_relationship')->nullable();
            $table->string('next_of_kin_address')->nullable();
            $table->string('next_of_kin_alternative_phone_number')->nullable();
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('next_of_kin');
    }
}

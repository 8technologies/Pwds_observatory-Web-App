<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->text('name')->nullable();
            $table->string('address')->nullable();
            $table->text('phone_number')->nullable();
            $table->text('email')->nullable();
            $table->text('phone_number_2')->nullable();
            $table->text('dob')->nullable();
            $table->text('sex')->nullable();
            $table->text('photo')->nullable();
            $table->foreignId('district_of_origin')->nullable()->constrained('districts');

            $table->string('other_names')->nullable();
            $table->string('id_number')->nullable(); // National ID, Passport, etc
            $table->string('ethnicity')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('religion')->nullable();
            $table->string('place_of_birth')->nullable(); // Hospital, Other
            $table->string("birth_hospital")->nullable();
            $table->string("birth_no_hospital_description")->nullable(); // if pwd not born in hospital
            $table->string('languages')->nullable(); // JSON array of languages

            $table->string('employer')->nullable();
            $table->string('position')->nullable();
            $table->string('year_of_employment')->nullable();

            $table->foreignId('district_id')->nullable()->constrained('districts'); // We map the district so to map the DU
            $table->foreignId('opd_id')->nullable()->constrainted('organisations');

            $table->text('aspirations')->nullable();
            $table->text('skills')->nullable();
            $table->text('profiler')->nullable();         

            $table->boolean('is_formal_education')->default(false);
            $table->boolean('is_employed')->default(false);
            $table->boolean('is_member')->default(false);
            $table->boolean('is_same_address')->default(false);
            $table->boolean('is_formerly_employed')->default(false);
            $table->boolean('is_approved')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('people');
        Schema::enableForeignKeyConstraints();
    }
}

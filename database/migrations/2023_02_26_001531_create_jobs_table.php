<?php

use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->enum('type', ['fulltime', 'parttime', 'contract', 'internship', 'volunteer','remote'])->nullable();
            $table->string('minimum_academic_qualification')->nullable();
            $table->string('required_experience')->nullable();
            $table->string('photo')->nullable();
            $table->text('how_to_apply')->nullable();
            $table->string('hiring_firm')->nullable();
            $table->string('deadline')->nullable();
            // $table->foreignId('district_id')->constrained('districts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints(); // disable foreign key constraints
        Schema::dropIfExists('jobs');
        Schema::enableForeignKeyConstraints(); // enable foreign key constraints
    }
}

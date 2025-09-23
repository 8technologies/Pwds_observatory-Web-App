<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectAndMilestonesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            
            $table->foreignId('user_id')->nullable()->index();
            // $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('code');
            $table->text('description');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('funding_source', ['government', 'donor', 'self-funded', 'other'])->default('government');
            $table->integer('budget');
            $table->text('beneficiaries');
            $table->timestamps();
        });

        //milestones
        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->index();
            // $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('responsible_person');
            $table->enum('status', ['Not_started', 'Ongoing', 'Completed', 'Delayed', 'Cancelled'])->default('Not_started');
            $table->date('completion_date')->nullable();
            $table->integer('milestone_progress')->default(0);
            $table->json('attachments')->nullable();
            
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
        Schema::dropIfExists('projects');
        Schema::dropIfExists('milestones');
    }
}

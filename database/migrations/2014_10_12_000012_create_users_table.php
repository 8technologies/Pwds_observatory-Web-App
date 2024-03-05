<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('password');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('reg_date')->nullable();
            $table->string('last_seen')->nullable();
            $table->string('email')->nullable();
            $table->boolean('approved')->default(false);
            $table->string('profile_photo')->nullable();
            $table->string('user_type')->nullable();
            $table->string('gender')->nullable();
            $table->string('reg_number')->nullable();
            $table->string('country')->nullable();
            $table->string('occupation')->nullable();
            $table->string('profile_photo_large')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('location_lat')->nullable();
            $table->string('location_long')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('website')->nullable();
            $table->string('other_link')->nullable();
            $table->text('cv')->nullable();
            $table->string('language')->nullable();
            $table->text('about')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
            $table->string('remember_token', 100)->nullable();
            $table->string('avatar')->nullable();
            $table->string('name')->nullable();
            $table->bigInteger('compass_id')->nullable();
            $table->boolean('complete_profile')->nullable();
            $table->string('title')->nullable();
            $table->timestamp('dob')->nullable();
            $table->text('intro')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

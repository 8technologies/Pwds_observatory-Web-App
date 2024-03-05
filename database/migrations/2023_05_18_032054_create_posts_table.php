<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('post_title');
            $table->longText('post_body')->nullable();
            $table->string('post_date')->nullable();
            $table->bigInteger('post_category');
            $table->bigInteger('post_by');
            $table->string('post_thumbnail')->nullable();
            $table->text('post_text')->nullable();
            $table->boolean('published')->default(0);
            $table->string('video_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}

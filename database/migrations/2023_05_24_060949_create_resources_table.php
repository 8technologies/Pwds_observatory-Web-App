<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->enum('type',['multimedia','publication','dataset','other']);
            $table->string('other_type')->nullable();
            $table->date('date_of_publication')->nullable();
            $table->text('description')->nullable();
            $table->string('author')->nullable(); // Could be the system user or other
            $table->string('cover_photo')->nullable();
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
        Schema::dropIfExists('resources');
    }
}

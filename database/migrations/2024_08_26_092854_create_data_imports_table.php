<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_imports', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(User::class, 'user_id')->nullable();
            $table->text('title')->nullable();
            $table->text('file')->nullable();
            $table->string('processed')->default('No');
            $table->string('has_error')->default('No');
            $table->text('error_message')->nullable();
            //total records
            $table->integer('total_records')->default(0)->nullable();
            //total records imported
            $table->integer('total_imported')->default(0)->nullable();
            //total records failed
            $table->integer('total_failed')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_imports');
    }
}

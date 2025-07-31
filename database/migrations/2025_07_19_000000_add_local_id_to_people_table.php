<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocalIdToPeopleTable extends Migration
{
    public function up()
    {
        Schema::table('people', function (Blueprint $table) {
            $table->text('local_id')->nullable();           
        });
    }

    public function down()
    {
        Schema::table('people', function (Blueprint $table) {

            $table->dropColumn('local_id');
        });
    }
}

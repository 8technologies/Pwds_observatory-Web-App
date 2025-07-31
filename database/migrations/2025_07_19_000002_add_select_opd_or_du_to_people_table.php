<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSelectOpdOrDuToPeopleTable extends Migration
{
    public function up()
    {
        Schema::table('people', function (Blueprint $table) {
            if (!Schema::hasColumn('people', 'select_opd_or_du')) {
                $table->string('select_opd_or_du')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('people', function (Blueprint $table) {
            if (Schema::hasColumn('people', 'select_opd_or_du')) {
                $table->dropColumn('select_opd_or_du');
            }
        });
    }
}

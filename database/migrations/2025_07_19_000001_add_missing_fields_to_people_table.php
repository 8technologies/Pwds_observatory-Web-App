<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToPeopleTable extends Migration
{
    public function up()
    {
        Schema::table('people', function (Blueprint $table) {
            // Check and add only missing fields
            if (!Schema::hasColumn('people', 'education_level')) {
                $table->string('education_level')->nullable();
            }
            
            if (!Schema::hasColumn('people', 'next_of_kin_name')) {
                $table->string('next_of_kin_name')->nullable();
            }
            
            if (!Schema::hasColumn('people', 'next_of_kin_phone')) {
                $table->string('next_of_kin_phone')->nullable();
            }
            
            if (!Schema::hasColumn('people', 'next_of_kin_relationship')) {
                $table->string('next_of_kin_relationship')->nullable();
            }
            
            if (!Schema::hasColumn('people', 'next_of_kin_email')) {
                $table->string('next_of_kin_email')->nullable();
            }
            
            if (!Schema::hasColumn('people', 'next_of_kin_address')) {
                $table->text('next_of_kin_address')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('people', function (Blueprint $table) {
            if (Schema::hasColumn('people', 'education_level')) {
                $table->dropColumn('education_level');
            }
            if (Schema::hasColumn('people', 'next_of_kin_name')) {
                $table->dropColumn('next_of_kin_name');
            }
            if (Schema::hasColumn('people', 'next_of_kin_phone')) {
                $table->dropColumn('next_of_kin_phone');
            }
            if (Schema::hasColumn('people', 'next_of_kin_relationship')) {
                $table->dropColumn('next_of_kin_relationship');
            }
            if (Schema::hasColumn('people', 'next_of_kin_email')) {
                $table->dropColumn('next_of_kin_email');
            }
            if (Schema::hasColumn('people', 'next_of_kin_address')) {
                $table->dropColumn('next_of_kin_address');
            }
        });
    }
}

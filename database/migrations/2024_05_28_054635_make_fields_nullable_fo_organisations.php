<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeFieldsNullableFoOrganisations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organisations', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->text('membership_type')->nullable()->change();
            $table->text('relationship_type')->nullable()->change();
            $table->text('region_id')->nullable()->change();
            /* 
            	
registration_number	
date_of_registration	
user_id	
mission	
vision	
core_values	
brief_profile	
membership_type	
district_id	
physical_address	
attachments	
logo	
certificate_of_registration	
admin_email	
valid_from	
valid_to	
relationship_type	
parent_organisation_id	
created_at	
updated_at	
region_id	
	

            */
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organisations', function (Blueprint $table) {
            //
        });
    }
}

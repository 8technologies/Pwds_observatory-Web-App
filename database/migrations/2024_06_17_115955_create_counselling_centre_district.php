<?php

use App\Models\CounsellingCentre;
use App\Models\District;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCounsellingCentreDistrict extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counselling_centre_district', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CounsellingCentre::class);
            $table->foreignIdFor(District::class);
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
        Schema::dropIfExists('counselling_centre_district');
    }
}

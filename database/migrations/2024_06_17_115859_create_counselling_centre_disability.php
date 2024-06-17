<?php

use App\Models\CounsellingCentre;
use App\Models\Disability;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCounsellingCentreDisability extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counselling_centre_disability', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CounsellingCentre::class);
            $table->foreignIdFor(Disability::class);
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
        Schema::dropIfExists('counselling_centre_disability');
    }
}

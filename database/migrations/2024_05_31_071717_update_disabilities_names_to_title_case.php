<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class UpdateDisabilitiesNamesToTitleCase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('title_case', function (Blueprint $table) {
            //
            DB::table('disabilities')->get()->each(function ($disability) {
                $titleCaseName = Str::title(strtolower($disability->name));
                DB::table('disabilities')
                    ->where('id', $disability->id)
                    ->update(['name' => $titleCaseName]);
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('title_case', function (Blueprint $table) {
            //
        });
    }
}

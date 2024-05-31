<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CorrectDisabilityNames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $nameCorrections = [
            'Visualimpairment' => 'Visual Impairment',
            'Hardofhearing' => 'Hard Of Hearing',
            'Physicaldisability' => 'Physical Disability',
            'Mentaldisability' => 'Mental Disability',
            'Intellectualdisability' => 'Intellectual Disability',
            'Celebralpalsy' => 'Celebral Palsy',
            'Deafblid' => 'Deaf Blind',
            'Acquiredbraininjury' => 'Acquired Brain Injury',

            // Add all other incorrect to correct mappings here
        ];

        foreach ($nameCorrections as $incorrect => $correct) {
            DB::table('disabilities')
                ->where('name', $incorrect)
                ->update(['name' => $correct]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

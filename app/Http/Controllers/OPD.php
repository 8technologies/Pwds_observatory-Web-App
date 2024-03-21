<?php

namespace App\Http\Controllers;

use App\Models\Api_Utils;
use App\Models\Organisation;
use Illuminate\Http\Request;

class OPD extends Controller
{

    public function index()
    {
        //return all OPD
        try {
            $opd = Organisation::where('relationship_type', 'opd')->get();
            return Api_Utils::success($opd, "OPD successfully returned", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 400);
        }
    }


    public function store(Request $request)
    {
        //create OPD
        $validate_opd = $request->validate([
            'name' => 'required',
            'vision' => 'required',
            'mission' => 'required',
            'core_values' => 'required',
            'brief_profile' => 'required',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

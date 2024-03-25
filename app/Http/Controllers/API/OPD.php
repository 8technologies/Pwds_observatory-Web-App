<?php

namespace App\Http\Controllers\API;

use App\Models\Api_Utils;
use App\Http\Controllers\Controller;
use App\Http\Requests\OpdStoreRequest;
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


    public function store(OpdStoreRequest $request)
    {
        //create OPD
        $requestedData = $request->all();
        $requestedData['relationship_type'] = 'opd';
        try {
            $opd = Organisation::create($requestedData);
            $opd->districtsOfOperation()->attach($request->input('districtsOfOperation'));
            return Api_Utils::success($opd, "OPD created successfully", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //show opd by id, where relationship_type is opd
        try {
            $opd = Organisation::where('relationship_type', 'opd')->findOrFail($id);
            return Api_Utils::success($opd, "OPD returned", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OpdStoreRequest $request, $id)
    {
        //update an opd
        $requestedData = $request->all();
        $requestedData['relationship_type'] = 'opd';
        try {
            $opd = Organisation::findOrFail($id);
            $opd->update($request->all());
            $opd->districtsOfOperation()->sync($request->input('districtsOfOperation'));
            return Api_Utils::success($opd, "OPD updated", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete an opd, where relationship_type is opd
        try {
            $opd = Organisation::where('relationship_type', 'opd')->findOrFail($id);
            $opd->delete();
            return Api_Utils::success("OPD successfully deleted", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 400);
        }
    }
}

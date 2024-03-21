<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeDuRequest;
use App\Models\Api_Utils;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class District_Union extends Controller
{

    public function index()
    {
        //Display list of all district unions
        try {
            $dus = Organisation::where('relationship_type', 'du')->get();
            return Api_Utils::success($dus, "District Unions successfully returned", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 400);
        }
    }

    public function store(storeDuRequest $request)
    {

        if ($request->district_id) {
            return Api_Utils::error("District Union already exists", 400);
        }

        $requestedData = $request->all();
        $requestedData['relationship_type'] = 'du';

        try {
            $du = Organisation::create($requestedData);
            return Api_Utils::success($du, "District Union created", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 400);
        }
    }


    public function show($id)
    {
        //show a specific district union
        try {
            $du = Organisation::FindorFail($id);
            return Api_Utils::success($du, "District Union returned", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 400);
        }
    }

    public function update(storeDuRequest $request, $id)
    {
        //if district_id exists, then district union must not be stored
        if ($request->district_id) {
            return Api_Utils::error("District Union already exists", 400);
        }

        $requestedData = $request->all();
        $requestedData['relationship_type'] = 'du';
        try {
            $du = Organisation::FindorFail($id);
            $du->update($request->all());
            return Api_Utils::success($du, "District Union updated", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 400);
        }
    }


    public function destroy($id)
    {
        //delete a district union
        try {
            $du = Organisation::FindorFail($id);
            $du->delete();
            return Api_Utils::success($du, "District Union deleted", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 400);
        }
    }
}

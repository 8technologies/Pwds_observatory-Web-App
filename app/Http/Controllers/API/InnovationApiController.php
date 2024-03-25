<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Api_Utils;
use App\Models\Innovation;
use Illuminate\Http\Request;

class InnovationApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //list all innovations
        try {
            $innovations = Innovation::all();
            return Api_Utils::success($innovations, 'Innovations retrieved successfully', 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //create a new innovation
        try {
            $innovation = Innovation::create($request->all());
            return Api_Utils::success($innovation, 'Innovation created successfully', 201);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 500);
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
        //get a single innovation
        try {
            $innovation = Innovation::find($id);
            if ($innovation) {
                return Api_Utils::success($innovation, 'Innovation retrieved successfully', 200);
            } else {
                return Api_Utils::error('Innovation not found', 404);
            }
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 500);
        }
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
        //update innovation
        try {
            $innovation = Innovation::find($id);
            if ($innovation) {
                $innovation->update($request->all());
                return Api_Utils::success($innovation, 'Innovation updated successfully', 200);
            } else {
                return Api_Utils::error('Innovation not found', 404);
            }
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 500);
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
        //delete innovation
        try {
            $innovation = Innovation::find($id);
            if ($innovation) {
                $innovation->delete();
                return Api_Utils::success('Innovation deleted successfully', 200);
            } else {
                return Api_Utils::error('Innovation not found', 404);
            }
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 500);
        }
    }
}

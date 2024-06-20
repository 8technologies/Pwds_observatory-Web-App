<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CounsellingCentreRequest;
use App\Models\Api_Utils;
use App\Models\CounsellingCentre;
use Illuminate\Http\Request;

class CounsellingAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        try {
            $counselling_centres = CounsellingCentre::all();

            if ($counselling_centres->isEmpty()) {
                throw new \Exception("No counselling centres retrieved from the database.");
            }
            return Api_Utils::success($counselling_centres, "Counselling centres successfully returned", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 500); // Changed to 500 to indicate server error
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
        try {
            $counselling_centres = CounsellingCentre::create($request->all());
            $counselling_centres->disabilities()->attach($request->input('disabilities'));
            $counselling_centres->districts()->attach($request->input('districts'));
            return Api_Utils::success($counselling_centres, 'Counselling Centre stored successfully', 200);
        } catch (\Exception $e) {
            return Api_Utils::error([
                'error' => $e->getMessage(),
                'message' => 'Failed to store counselling centre',
                'status' => 500
            ]);
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
        //
        try {
            $counselling_centres = CounsellingCentre::find($id);
            if ($counselling_centres) {
                return Api_Utils::success($counselling_centres, 'Counselling Centre returned successfully', 200);
            } else {
                return Api_Utils::error(
                    'Counseling Centre not found',
                    404
                );
            }
        } catch (\Exception $e) {
            return Api_Utils::error([
                'error' => $e->getMessage(),
                'message' => 'Failed to get counselling centre',
                'status' => 500
            ]);
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
        //
        try {
            $counselling_centres = CounsellingCentre::find($id);
            if ($counselling_centres) {
                $counselling_centres->update($request->all());
                return Api_Utils::success($counselling_centres, 'Counselling centre updated successfully', 200);
            } else {
                return Api_Utils::error(
                    'Counselling centre not found',
                    404
                );
            }
        } catch (\Exception $e) {
            return Api_Utils::error([
                'error' => $e->getMessage(),
                'message' => 'Failed to update counselling centre',
                'status' => 500
            ]);
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
        //
        try {
            $counselling_centres = CounsellingCentre::find($id);
            if ($counselling_centres) {
                $counselling_centres->delete();
                return Api_Utils::success($counselling_centres, 'Counselling centre deleted successfully', 200);
            } else {
                return Api_Utils::error(
                    'Counselling centres not found',
                    404
                );
            }
        } catch (\Exception $e) {
            return Api_Utils::error([
                'error' => $e->getMessage(),
                'message' => 'Failed to delete counselling centres',
                'status' => 500
            ]);
        }
    }
}

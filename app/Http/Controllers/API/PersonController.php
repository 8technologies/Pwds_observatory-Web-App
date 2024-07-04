<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\PeopleStoreRequest;
use App\Models\Person as ModelsPerson;
use App\Http\Controllers\Controller;
use App\Models\Api_Utils;
use App\Models\Organisation;
use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Imports\ModelManager;

class PersonController extends Controller
{
    //function for returning all people
    public function index(Request $request)
    {
        $user = $request->user(); // Assuming the authenticated user is retrieved from the request
        if ($user == null) {
            return Api_Utils::error("User not found", 404);
        }

        try {
            $organisation = Organisation::find($user->organisation_id);
            if (!$organisation) {
                return Api_Utils::error("Organisation not found", 404);
            }

            $query = ModelsPerson::query();

            if ($user->inRoles(['nudipu', 'administrator'])) {
                $query->orderBy('created_at', 'desc');
            } elseif ($user->isRole('district-union')) {
                $query->where('district_id', $organisation->district_id)->orderBy('created_at', 'desc');
            } elseif ($user->isRole('opd')) {
                $query->where('opd_id', $organisation->id)->orderBy('created_at', 'desc');
            } else {
                return Api_Utils::error("User role is not authorized", 403);
            }

            $people = $query->paginate($request->per_page);

            if ($people->isEmpty()) {
                return Api_Utils::error("No data retrieved from the database.", 404);
            }

            return Api_Utils::success($people, "People successfully returned", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 500); // Changed to 500 to indicate server error
        }
    }


    //function for creating a new person
    public function store(PeopleStoreRequest $request)
    {
        //Creating person and storing them to the databas

        try {
            $person = new ModelsPerson();
            $person = ModelsPerson::create($request->all());
            $person->disabilities()->attach($request->input('disabilities'));
            return Api_Utils::success($person, "Person created", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 400);
        }
    }

    //function for retrieving data for a specific person
    public function show($id)
    {
        //retrieve a person from the database
        try {
            $person = ModelsPerson::FindorFail($id);
            return Api_Utils::success($person, "Person returned", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 400);
        }
    }

    //function for updating a resord    
    public function update(PeopleStoreRequest $request, $id)
    {

        //updating a person
        try {
            $person = ModelsPerson::findOrFail($id);
            $person->update($request->all());
            $person->disabilities()->sync($request->input('disabilities'));
            return Api_Utils::success($person, "Person updated successfully", 200);
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
        //delete a person
        try {
            $person = ModelsPerson::FindorFail($id);
            $person->delete();
            return Api_Utils::success($person, "Person deleted", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 400);
        }
    }
}

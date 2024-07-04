<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\PeopleStoreRequest;
use App\Models\Person as ModelsPerson;
use App\Http\Controllers\Controller;
use App\Models\Api_Utils;
use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Imports\ModelManager;

class PersonController extends Controller
{
    //function for returning all people
    public function index(Request $request)
    {
        $u = $request->user();
        if ($u == null) {
            return Api_Utils::error("User not found", 404);
        }
        $u = Administrator::find($u->id);
        try {
            
            $conds['organisation_id'] = $u->organisation_id;

            //$people = ModelsPerson::paginate($request->per_page);
            $people = ModelsPerson::where($conds)->paginate($request->per_page);

            if ($people->isEmpty()) {
                throw new \Exception("No data retrieved from the database.");
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

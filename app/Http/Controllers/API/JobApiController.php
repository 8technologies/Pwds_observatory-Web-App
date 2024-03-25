<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobStoreRequest;
use App\Models\Api_Utils;
use App\Models\Job;
use Illuminate\Http\Request;

class JobApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //retrieve all jobs
        try {
            $jobs = Job::all();
            return Api_Utils::success($jobs, "Jobs successfully returned", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JobStoreRequest $request)
    {
        //create jobs
        try {
            $job = Job::create($request->all());
            return Api_Utils::success($job, "Job successfully created", 201);
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
        //show a job
        try {
            $job = Job::find($id);
            return Api_Utils::success($job, "Job successfully returned", 200);
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
        //delete a job
        try {
            $job = Job::find($id);
            $job->delete();
            return Api_Utils::success($job, "Job successfully deleted", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 400);
        }
    }
}

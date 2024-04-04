<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobStoreRequest;
use App\Models\Api_Utils;
use App\Models\Job;
use Illuminate\Http\Request;

class JobApiController extends Controller
{

    public function index()
    {
        //retrieve all jobs
        try {
            $jobs = Job::all();
            if (!$jobs) {
                return Api_Utils::error("Jobs not found", 404);
            }
            return Api_Utils::success($jobs, "Jobs successfully returned", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 400);
        }
    }

    public function store(JobStoreRequest $request)
    {
        //create jobs
        try {
            $job = Job::create($request->all());
            if ($job) {
                return Api_Utils::success($job, "Job successfully created", 201);
            }
            return Api_Utils::error("Job not created", 400);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 400);
        }
    }

    public function show($id)
    {
        //show a job
        try {
            $job = Job::find($id);
            if (!$job) {
                return Api_Utils::error("Job not found", 404);
            }
            return Api_Utils::success($job, "Job successfully returned", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 400);
        }
    }


    public function update(Request $request, $id)
    {
        //edit a job
        try {
            $job = Job::find($id);
            if (!$job) {
                return Api_Utils::error("Job not found", 404);
            }
            $job->update($request->all());
            return Api_Utils::success($job, "Job successfully updated", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 400);
        }
    }


    public function destroy($id)
    {
        //delete a job
        try {
            $job = Job::find($id);
            if (!$job) {
                return Api_Utils::error("Job not found", 404);
            }
            $job->delete();
            return Api_Utils::success($job, "Job successfully deleted", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 400);
        }
    }
}

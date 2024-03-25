<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceProviderStoreRequest;
use App\Models\Api_Utils;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;

class ServiceProviderAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //list of service providers
        try {
            $service_providers = ServiceProvider::all();
            return Api_Utils::success($service_providers, "Service Providers successfully returned", 200);
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
    public function store(ServiceProviderStoreRequest $request)
    {
        //storing service providers
        try {
            $service_provider = ServiceProvider::create($request->all());
            var_dump($request->input('disability_categories'));
            die();
            $service_provider->disability_categories()->attach($request->input('disability_categories'));
            $service_provider->districts()->attach($request->input('districts'));
            return Api_Utils::success($service_provider, "Service Provider successfully created", 201);
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

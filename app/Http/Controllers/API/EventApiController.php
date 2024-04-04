<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventStoreRequest;
use App\Models\Api_Utils;
use App\Models\Event;
use Illuminate\Http\Request;

class EventApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //list all events
        try {
            $events = Event::all();
            return Api_Utils::success($events, 'Events retrieved successfully', 200);
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
    public function store(EventStoreRequest $request)
    {
        //store event
        try {
            $event = Event::create($request->all());
            if (!$event) {
                return Api_Utils::error('Event not created', 500);
            }
            return Api_Utils::success($event, 'Event created successfully', 200);
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
        //event details
        try {
            if ($event = Event::find($id)) {
                return Api_Utils::success($event, 'Event retrieved successfully', 200);
            }
            return Api_Utils::error('Event not found', 404);
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
    public function update(EventStoreRequest $request, $id)
    {
        //update event
        try {
            if ($event = Event::find($id)) {
                if ($event->update($request->all())) {
                    return Api_Utils::success($event, 'Event updated successfully', 200);
                }
                return Api_Utils::error('Event not updated', 500);
            }
            return Api_Utils::error('Event not found', 404);
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
        //delete event
        try {
            if ($event = Event::find($id)) {
                if ($event->delete()) {
                    return Api_Utils::success($event, 'Event deleted successfully', 200);
                }
                return Api_Utils::error('Event not deleted', 500);
            }
            return Api_Utils::error('Event not found', 404);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 500);
        }
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsPostStoreRequest;
use App\Models\Api_Utils;
use App\Models\NewsPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsPostApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //list all news posts
        try {
            if ($news_posts = NewsPost::all()) {
                return Api_Utils::success($news_posts, "News posts retrieved successfully", 200);
            }
            return Api_Utils::error("No news posts found", 404);
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
    public function store(NewsPostStoreRequest $request)
    {
        //create a news post
        try {
            if ($news_post = NewsPost::create($request->all())) {
                return Api_Utils::success($news_post, "News post created successfully", 200);
            }
            return Api_Utils::error("Failed to create news post", 500);
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
        //show a news post
        try {
            if ($news_post = NewsPost::find($id)) {
                return Api_Utils::success($news_post, "News post retrieved successfully", 200);
            }
            return Api_Utils::error("News post not found", 404);
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
    public function update(NewsPostStoreRequest $request, $id)
    {
        //update a news post
        try {
            if ($news_post = NewsPost::find($id)) {
                $news_post->update($request->all());
                return Api_Utils::success($news_post, "News post updated successfully", 200);
            }
            return Api_Utils::error("News post not found", 404);
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
        //delete a news post
        try {
            $news_post = NewsPost::find($id);
            if ($news_post) {
                $news_post->delete();
                return Api_Utils::success($news_post, "News post deleted successfully", 200);
            }
            return Api_Utils::error("News post not found", 404);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 500);
        }
    }
}

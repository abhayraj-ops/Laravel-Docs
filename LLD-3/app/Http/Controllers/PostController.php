<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();

        return response()->json([
            'data' => $posts
        ]);      
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(StorePostRequest $request)
    {
        $validateData = $request->validated();

        $post = Post::create($validateData);

        return response()->json([
            'message' => 'Post Successfully Created!',
            'data' => $post
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = Post::find($id);

        return response()->json([
            'data' => $post
        ]);  
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, $id)
    {
        $post = Post::find($id);

        $validateData = $request->validated();

        $post->title = $validateData['title'];
        $post->user_id = $validateData['user_id'];
        $post->content = $validateData['content'];

        $post->save();

        return response()->json([
            'message' => 'Post Updated Successfully',
            'data' => $post
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Post::destroy($id);
        return response()->json([
            'message' => 'Post Deleted Successfully'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Model\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PostResource::collection(Post::orderBy('created_at', 'desc')->get());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $file = $request->image;

        //get image extension
        $extension = $file->getClientOriginalExtension();

        //name the image
        $name = time() . '.' . $extension;

        //move the file
        $file->move(public_path() . '/images/post/', $name);



        $post = new Post;
        $post->title = $request->title;
        $now = time();
        $post->slug = Str::slug($request->title . '' . $now);
        $post->body = $request->body;
        $post->image = $name;
        $post->user_id = $request->user_id;
        $post->save();

        //return new PostResource($post);
        return response([
            'message' => 'Created Successfully',
            'data' => new PostResource($post), 'status' => Response::HTTP_CREATED
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($post)
    {
        //return $post->first();
        if (!$post = Post::where('slug', $post)->first()) {
            return response([
                'message' => 'Invalid Parameter Passed',
                'errors' => ['post' => ['No data Found']]
            ]);
        }
        return new PostResource($post);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, $post)
    {

        if (!$post = Post::where('slug', $post)->first()) {
            return; //helps us to handle an exception error in reactJs
        }


        if (!empty($request->file('image'))) {
            $file = $request->image;

            //get image extension
            $extension = $file->getClientOriginalExtension();

            //name the image
            $name = time() . '.' . $extension;

            //move the file
            $file->move(public_path() . '/images/post/', $name);
        }

        //$post = Post::where('slug', $post)->first();
        if ($post->title != $request->title) {
            $post->title = $request->title;
            $now = time();
            $post->slug = Str::slug($request->title . '' . $now);
        }

        $post->body = $request->body;
        if (isset($name)) {
            $post->image = $name;
        }
        $post->save();

        //return new PostResource($post);
        return response([
            'message' => 'Updated Successfully',
            'data' => new PostResource($post), 'status' => Response::HTTP_ACCEPTED
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if (file_exists(public_path('images/' . 'post/' . $post->image))) {
            unlink(public_path('images/' . 'post/' . $post->image));
        }

        $post->delete();
        //return 'deleted';
        return response(null, Response::HTTP_NO_CONTENT);
    }
}

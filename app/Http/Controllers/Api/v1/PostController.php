<?php

namespace App\Http\Controllers\Api\v1;

use App\Course;
use App\Http\Resources\v1\PostCollection;
use App\Http\Resources\v1\Post as PostResource;
use App\Post;
use App\User;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class PostController extends Controller
{


    public function __construct(){

        $this->middleware('can:post-show-all')->only(['index']);
        $this->middleware('can:post-show')->only(['show']);
        $this->middleware('can:post-store')->only(['store']);
        $this->middleware('can:post-update')->only(['update']);
        $this->middleware('can:post-delete')->only(['destroy']);

    }


    /**
     * Display a listing of the resource.
     *
     * @return PostCollection
     */
    public function index()
    {
        $posts = Post::withCount('comments')->latest()->paginate(2);


        return new PostCollection($posts);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validData = $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'body' => 'required',
            'categories' => 'required|array|exists:categories,id',
            'image' => 'required|mimes:jpeg,bmp,png|max:10240'
        ]);

       // dd($validData);

        $thumbImage = $this->uploadImg($request);


        $post = auth()->user()->posts()->create(array_merge($validData,$thumbImage));
        //$post = auth()->user()->posts()->create($validData);
        $post->categories()->sync($validData['categories']);


        return response()->json([
            'data'=> [
                'message' => 'Post created successfully'
            ],
            'status' => 'success'
        ]);


    }


    /**
     * Display the specified resource.
     *
     * @param Post $post
     * @return PostResource
     */
    public function show(Post $post)
    {
        $post->update(['viewCount'=>$post->viewCount+1]);

        return new PostResource($post);
    }



    /**
     * Display the specified resource.
     *
     * @param Post $post
     * @return PostResource
     */
    public function like(Post $post)
    {
        $post->update(['like'=>$post->like+1]);

        return new PostResource($post);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {

        $validData = $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'body' => 'required',
            'categories' => 'required|array|exists:categories,id'
        ]);

        if($request->image) {
            $request->validate([
                'image' => 'required|mimes:jpeg,bmp,png|max:10240'
            ]);
        }

        // dd($validData);

        if($request->file('image')) {
            $thumbImage = $this->uploadImg($request);
             $post->update(array_merge($validData,$thumbImage));
        }else{
             $post->update($validData);
        }


        //$post = auth()->user()->posts()->create($validData);
        $post->categories()->sync($validData['categories']);


        return response()->json([
            'data'=> [
                'message' => 'Post Updated Successfully.'
            ],
            'status' => 'success'
        ]);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Post $post
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json([
            'data'=> [
                'message' => 'Post Deleted successfully!'
            ],
            'status' => 'success'
        ]);
    }




    public function uploadImg(Request $request ){

        $this->validate($request , [
            'image' => 'required|mimes:jpeg,bmp,png|max:10240'
        ]);

        $file = $request->file('image');

        $imagePath = "/upload/images";
        $filename = $file->getClientOriginalName();

        $fileSystem = new Filesystem();

        if($fileSystem->exists(public_path("{$imagePath}/{$filename}"))) {
            $filename = Carbon::now()->timestamp . "-{$filename}";
        }

        $file->move(public_path($imagePath) , $filename);

        return  ['thumbImage'=>url("{$imagePath}/{$filename}")];
    }

}

<?php

namespace App\Http\Controllers\Api\v1;

use App\Comment;
use App\Http\Resources\v1\CommentCollection;
use App\Http\Resources\v1\Comment as CommentResource;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{

    public function __construct(){

        $this->middleware('can:comment-show-all')->only(['index']);
        //$this->middleware('can:comment-show')->only(['show']);
        $this->middleware('can:comment-store')->only(['store']);
        $this->middleware('can:comment-update')->only(['update']);
        $this->middleware('can:comment-delete')->only(['destroy']);

    }


    /**
     * Display a listing of the resource.
     *
     * @return CommentCollection
     */
    public function index(){

        $comments= Comment::latest()->paginate(10);

        return new CommentCollection($comments);
    }

    /**
     * Display the specified resource.
     *
     * @param Comment $comment
     * @return CommentResource
     */
    public function show(Comment $comment)
    {

        return new CommentResource($comment);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request){


        $validData = $this->validate($request,[
            'body' => 'required',
            'post_id'=>'required|exists:posts,id'
        ]);

        if($request->parent_id) {
            $request->validate([
                'parent_id' => 'integer|exists:comments,id'
            ]);
            $validData['parent_id']=$request->parent_id;
        }



        auth()->user()->comments()->create($validData);

        return response()->json([
            'data'=> [
                'message' => 'Comment created successfully.'
                 ],
            'status' => 'success'
        ]);

    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request,Comment $comment){


        $validData = $this->validate($request,[
            'body' => 'required',
        ]);



        $comment->update($validData);

        return response()->json([
            'data'=> [
                'message' => 'Comment Update successfully.'
            ],
            'status' => 'success'
        ]);

    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reply(Request $request, Comment $comment){


        $validData = $this->validate($request,[
            'body' => 'required',
        ]);

        $validData['parent_id']= $comment->id;
        $validData['post_id']  = $comment->post_id;



        auth()->user()->comments()->create($validData);

        return response()->json([
            'data'=> [
                'message' => 'Comment\'s Reply created successfully.'
            ],
            'status' => 'success'
        ]);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json([
            'data'=> [
                'message' => 'Comment Deleted successfully!'
            ],
            'status' => 'success'
        ]);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCascade(Comment $comment){

        if($comment->children){
            foreach($comment->children as $child){
                $this->deleteCascade($child);
            }
        }

        $comment->delete();

        return response()->json([
            'data'=> [
                'message' => 'Comment and Comment children Deleted successfully!'
            ],
            'status' => 'success'
        ]);

    }

}

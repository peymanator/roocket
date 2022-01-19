<?php

namespace App\Http\Controllers\Api\v1;

use App\Category;
use App\Http\Resources\v1\Category as CategoryResource;
use App\Http\Resources\v1\CategoryCollection;
use App\Http\Resources\v1\Post as PostResource;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{

    public function __construct(){

        $this->middleware('can:category-show-all')->only(['index']);
        $this->middleware('can:category-show')->only(['show']);
        $this->middleware('can:category-store')->only(['store']);
        $this->middleware('can:category-update')->only(['update']);
        $this->middleware('can:category-delete')->only(['destroy']);

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = category::latest()->paginate(10);

        return new CategoryCollection($categories);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->parent) {
            $request->validate([
                'parent_id' => 'exists:categories,id'
            ]);
        }

        $request->validate([
            'title' => 'required|min:3'
        ]);

        Category::create([
            'title' => $request->title,
            'parent_id' => $request->parent_id ?? 0
        ]);

        return response()->json([
            'data'=> [
                'message' => 'Category created successfully'
            ],
            'status' => 'success'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Category $category)
    {

//        dd($request->all());

        if($request->parent_id) {
            $request->validate([
                'parent_id' => 'exists:categories,id'
            ]);
        }

        $request->validate([
            'title' => 'required|min:3'
        ]);

        $category->update([
            'title' => $request->title,
            'parent_id' => $request->parent_id??$category->parent_id
        ]);


        return response()->json([
            'data'=> [
                'message' => 'Category Updated successfully.'
            ],
            'status' => 'success'
        ]);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'data'=> [
                'message' => 'Category Deleted successfully!'
            ],
            'status' => 'success'
        ]);
    }




}

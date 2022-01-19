<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Resources\v1\Permission as PermissionResource;
use App\Http\Resources\v1\PermissionCollection;
use App\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{

    public function __construct(){

        $this->middleware('can:permission-show-all')->only(['index']);
        $this->middleware('can:permission-show')->only(['show']);
        $this->middleware('can:permission-store')->only(['store']);
        $this->middleware('can:permission-update')->only(['update']);
        $this->middleware('can:permission-delete')->only(['destroy']);

    }




    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::latest()->paginate(10);

        return new PermissionCollection($permissions);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return PermissionResource
     */
    public function store(Request $request)
    {
        $data= $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions']
        ]);

        $permission = Permission::create($data);

        return new PermissionResource($permission);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Permission  $permission
     * @return PermissionResource
     */
    public function show(Permission $permission)
    {
        return new PermissionResource($permission);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $data= $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions')->ignore($permission->id)]
        ]);

        $permission->update($data);

        return new PermissionResource($permission);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return response()->json([
            'data'=> [
                'message' => 'Permission Deleted successfully!'
            ],
            'status' => 'success'
        ]);
    }
}

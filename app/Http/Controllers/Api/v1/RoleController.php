<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Resources\v1\Permission as PermissionResource;
use App\Http\Resources\v1\RoleCollection;
use App\Http\Resources\v1\Role as RoleResource;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return RoleCollection
     */
    public function index()
    {
        $roles = Role::latest()->paginate(10);

        return new RoleCollection($roles);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return RoleResource
     */
    public function store(Request $request)
    {
        $data= $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles'],
            'permissions' => ['array']
        ]);

        $role = Role::create($data);
        $role->permissions()->sync($request->permissions);

        return new RoleResource($role);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $role
     * @return RoleResource
     */
    public function show(Role $role)
    {
        return new RoleResource($role);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $role
     * @return RoleResource
     */
    public function update(Request $request, Role $role)
    {
        $data= $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($role->id)]
        ]);

        $role->update($data);
        $role->permissions()->sync($request->permissions);

        return new RoleResource($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Role $role)
    {
        $Role->delete();

        return response()->json([
            'data'=> [
                'message' => 'Role Deleted successfully!'
            ],
            'status' => 'success'
        ]);
    }
}

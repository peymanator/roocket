<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Resources\v1\UserCollection;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\User as UserResource;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


class UserController extends Controller
{



    public function __construct(){

        $this->middleware('can:post-show-all')->only(['index']);
        $this->middleware('can:post-show')->only(['show']);
        $this->middleware('can:post-store')->only(['store']);
        $this->middleware('can:post-update')->only(['update']);
        $this->middleware('can:post-delete')->only(['destroy']);

    }


    public function index(){

        $users= User::latest()->paginate(10);

        return new UserCollection($users);

    }


    public function login(Request $request){


        //validate
        //dd($request->all());

        $validData = $this->validate($request,[
            'email' => 'required|exists:users',
            'password' => 'required'
        ]);


        if(! auth()->attempt($validData)){

            return response([
                'data' => 'اطلاعات صحیح نیست',
                'status' => 'error'
            ],403);

        }

        auth()->user()->update([
            'api_token' => Str::random(120)
        ]);

        return new UserResource(auth()->user());


    }


    public function register(Request $request){

        $data = $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);


        //$user_type = ( $request->type == 'admin')?'admin':'user';

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'api_token' => Str::random(120),
            //'type' => $user_type
        ]);


        return new UserResource($user);

    }


    public function makeUserAdmin(Request $request,User $user){

        $user->update(['type'=>'admin']);
        return new UserResource($user);
    }



    public function update(Request $request,User $user){

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        if(! is_null($request->password)){
            $request->validate(['password'=>'required|string|min:6']);

            $data['password'] = $request->password;
        }


        $data['type'] = ( $request->type == 'admin')?'admin':'user';



        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'api_token' => Str::random(120),
            'type' => $data['type']
        ]);


        return new UserResource($user);

    }


    public function permissions(Request $request, User $user){

        $data= $request->validate([
            'roles' => ['array'],
            'permissions' => ['array'],
        ]);

        $user->permissions()->sync($request->permissions);
        $user->roles()->sync($request->roles);


        return new UserResource($user);
    }


}

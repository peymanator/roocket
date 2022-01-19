<?php

namespace App\Http\Middleware\Api\v1;

use App\User;
use Closure;




class AdminAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
       //dd($request->api_token);

       $user = User::where('api_token',$request->api_token)->first();

        if($user instanceof User and ($user->isAdmin()||$user->isSuperUser())){
            return $next($request);
        }

        return response()->json([
            'data'=> [
                'message' => 'Unauthenticated..'
            ],
            'status' => 'error'
        ],403);

    }
}

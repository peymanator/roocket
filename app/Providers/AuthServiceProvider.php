<?php

namespace App\Providers;

use App\Permission;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {

        $user = User::where('api_token',\request()->api_token)->first();

        Gate::before(function($user) {
            if($user->isSuperUser()) return true;
        });

        foreach (Permission::all() as $permission) {
            Gate::define($permission->name , function($user) use ($permission){
                return $user->hasPermission($permission);
            });
        }
    }
}

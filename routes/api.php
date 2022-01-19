<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::get('/articles',function(){

    return 'done';

});

Route::prefix('v1')->namespace('Api\v1')->group(function(){


    Route::post('/login','UserController@login');
    Route::post('/register','UserController@register');

    Route::get('/comments','CommentController@index');
    Route::get('/comment/{comment}','CommentController@show');

    Route::get('/posts','PostController@index');
    Route::get('/post/{post}','PostController@show');

    Route::get('/categories','CategoryController@index');
    Route::get('/category/{category}','CategoryController@show');

    Route::middleware(['auth:api','auth.admin'])->group(function () {

        Route::get('/user',function(){
            return auth()->user();
        });
        Route::get('/users','UserController@index');
        Route::post('/user/{user}/make-it-admin','UserController@makeUserAdmin');
        Route::patch('/user/{user}','UserController@update');
        Route::post('/user/{user}/set-permissions','UserController@permissions');


        Route::resource('/permissions','PermissionController');
        Route::resource('/roles','roleController');


        Route::post('/comment','CommentController@store');
        Route::post('/comment/{comment}/reply','CommentController@reply');
        Route::delete('/comment/{comment}','CommentController@destroy');
        Route::delete('comment/deletecascade/{comment}','CommentController@deleteCascade');
        Route::patch('/comment/{comment}','CommentController@update');


        Route::post('/post','PostController@store');
        Route::delete('/post/{post}','PostController@destroy');
        Route::patch('/post/{post}','PostController@update');
        Route::post('/post/{post}/like','PostController@like');


        Route::post('/category','CategoryController@store');
        Route::delete('/category/{category}','CategoryController@destroy');
        Route::patch('/category/{category}','CategoryController@update');

    });

});
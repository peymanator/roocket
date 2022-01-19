<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'api_token' => \Illuminate\Support\Str::random(120),
        'remember_token' => str_random(10),
        'type' => 'user'
    ];
});

$factory->define(App\Course::class, function(Faker $faker){

    return [
        'title' => $faker->sentence(),
        'body' => $faker->paragraph(5),
        'price' => $faker->numberBetween(1000,10000),
        'image' => $faker->imageUrl(),
    ];

});

$factory->define(App\Episode::class, function(Faker $faker){

    return [
        'title' => $faker->sentence(),
        'body' => $faker->paragraph(5),
        'videoUrl' => 'https://www.quirksmode.org/html5/videos/big_buck_bunny.mp4',
    ];

});

$factory->define(App\Post::class, function(Faker $faker){

    return [
        'title' => $faker->word(2),
        'description' => $faker->word(5),
        'body' => $faker->word(10),
        'thumbImage' => $faker->imageUrl(),
        'viewCount' =>rand(0,9),
        'like' => rand(0,10)
    ];

});

$factory->define(App\Comment::class, function(Faker $faker){

    return [
        'body' => $faker->word(5),
    ];

});

$factory->define(App\Category::class, function(Faker $faker){

    return [
        'title' => $faker->word(2),
    ];

});


$factory->define(App\Permission::class, function(Faker $faker){

    return [
        'name' => $faker->word(2),
    ];

});

$factory->define(App\Role::class, function(Faker $faker){

    return [
        'name' => $faker->word(2),
    ];

});


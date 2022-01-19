<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1;$i<=5;$i++){
            \App\User::create([
                'name'=>"peyman$i",
                'email'=>"peyman$i@mail.com",
                'password'=>bcrypt('123456'),
                'type'=>($i==1)?'super_admin':'admin',
                'api_token'=>\Illuminate\Support\Str::random(120)
                ]);
        }

        for($i=1;$i<=2;$i++){
            \App\User::create([
                'name'=>"ali$i",
                'email'=>"ali$i@mail.com",
                'password'=>bcrypt('123456'),
                'type'=>'user',
                'api_token'=>\Illuminate\Support\Str::random(120)
            ]);
        }



        $user = \App\User::find(1);
        factory(\App\Category::class , 5)->create()->each(function ($cat) {

            factory(\App\Post::class, 2)->make(['user_id'=>rand(1,7)])->each(function ($post,$key) use ($cat){
                $cat->posts()->save($post);
                factory(\App\Comment::class, 1)->make(['user_id'=>rand(1,7),'post_id'=>$post->id])->each(function ($comment,$key)use($post){
                    $comment->save();
                    factory(\App\Comment::class, 1)->make(['user_id'=>rand(1,7),'post_id'=>$post->id,'parent_id'=>$comment->id])->each(function ($comment2,$key){
                        $comment2->save();
                    });
                });
            });
            factory(\App\Category::class, 2)->make(['parent_id'=>$cat->id])->each(function ($cat2,$key) use ($cat){
                $cat2->save();
            });


        });



       // $r=['manager','editor'];
        $models = ['user','post','category','comment','permission'];
        $acts = ['show-all','show','update','delete','store'];

        foreach($models as $m){
            foreach($acts as $act){
                \App\Permission::create([
                    'name'=>"$m-$act"
                ]);
            }
        }


        $user1 = \App\User::find(1);
        $permissions= \App\Permission::all();

        $user1->permissions()->sync($permissions);


    }
}

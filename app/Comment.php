<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['body','parent_id','post_id'];

    public function post(){

        return $this->belongsTo(Post::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function children(){
        return $this->hasMany(Comment::class,'parent_id','id');
    }
}

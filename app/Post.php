<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title','description','body','thumbImage','viewCount','like'];

    public function categories(){
        return $this->belongsToMany(Category::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

}

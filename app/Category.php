<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['title','parent_id'];

    public function posts(){
        return $this->belongsToMany(Post::class);
    }

    public function children(){
        return $this->hasMany(Category::class,'parent_id','id');
    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['title','body','image','price'];

    public function episodes(){

        return $this->hasMany(Episode::class);
    }
}

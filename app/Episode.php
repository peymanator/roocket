<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $fillable = ['title','body','videoUrl','number'];

    public function course(){

        return $this->belongsTo(Course::class);
    }

}

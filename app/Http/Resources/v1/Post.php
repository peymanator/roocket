<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;

class Post extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title'=> $this->title,
            'description'=> $this->description,
            'body' => $this->body,
            'thumbImage' => $this->thumbImage,
            'viewCount' => $this->viewCount,
            'like' => $this->like,
            'comments_count' => $this->comments->count(),
            'categories' => new CategoryCollection($this->categories),
            'coments'=> new CommentCollection($this->comments)
        ];
    }
}

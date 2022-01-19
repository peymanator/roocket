<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;

class Comment extends Resource
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
            'parent_id' => $this->parent_id,
            'body' => $this->body,
            'children' => new CommentCollection($this->children),
            'post' => new Post($this->post)
        ];
    }
}

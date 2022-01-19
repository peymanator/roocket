<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'data' => $this->collection->map(function($item){
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'thumbImage' => $item->thumbImage,
                    'comments_count' => $item->comments_count,
                    'likeCount' => $item->like,
                    'viewCount' => $item->viewCount,
                ];
            })
        ];


    }
}

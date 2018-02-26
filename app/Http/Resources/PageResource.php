<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class PageResource extends Resource
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
            'title' => $this->title,
            'content' => $this->content,
            'short_text' => substr(strip_tags($this->content), 0, 150) . '...',
            'nav' => $this->nav,
            'images' => ImageResource::collection($this->images)
        ]; 
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Blog extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => (string)$this->id,
            'image' => $this->image,
            'title' => $this->title,
            'body' => $this->body,
            'user_id' => $this->user_id,
            'blog_category_id' => $this->blog_category_id,
            'comments' => BlogComment::collection($this->comments),
            'created_at' => $this->created_at
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogComment extends JsonResource
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
            "id" => (string)$this->id,
            "body" => $this->body,
            "blog_id" => $this->blog_id,
            "user_id" => $this->user_id,
            "parent_id" => $this->parent_id,
            "created_at" => $this->created_at,
            "replies" => BlogComment::collection($this->replies)
        ];
    }
}

<?php

namespace App\Http\Resources\ViewProfile;

use App\Http\Resources\Jobimage;
use App\Http\Resources\Keyword;
use Illuminate\Http\Resources\Json\JsonResource;

class AllJobProfile extends JsonResource
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
            'status' => $this->status,
            'title' => $this->title,
            'image' => Jobimage::collection($this->jobimages),
            'description' => $this->description,
            'keyword' => Keyword::collection($this->keywords),
            'price' => $this->price,
            'completein' => $this->completein,        ];
    }
}

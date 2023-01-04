<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Job extends JsonResource
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
            'title' => $this->title,
            'image' => explode(',',$this->image),
            'description' => $this->description,
            'keyword' => $this->keyword,
            'price' => $this->price,
            'completein' => $this->completein,
            'user' => $this->user_id,
            'category' => $this->category,
            'subcategory' => $this->subcategory,
        ];
    }
}
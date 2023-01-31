<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Profile extends JsonResource
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
            'id'=>(string)$this->id,
            'title' => $this->title,
            'description' => $this->description,
            'skills' => explode(',', $this->skills),
            'langs' => explode(',', $this->langs),
            'certification' => $this->certification,
            'nationalid' => $this->nationalid,
            'city_id' => new City($this->city),
            'age' => $this->age,
            'gender' => $this->gender
        ];
    }
}

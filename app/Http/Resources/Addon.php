<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Addon extends JsonResource
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
            'title'=>$this->title,
            'job_id'=>$this->job
        ];
    }
}

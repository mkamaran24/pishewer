<?php

namespace App\Http\Resources\Hero;

use Illuminate\Http\Resources\Json\JsonResource;

class JobTrans extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'completein' => $this->completein,
            'job_detail' => $this->job
        ];
    }
}

<?php

namespace App\Http\Resources\ViewProfile;

use Illuminate\Http\Resources\Json\JsonResource;

class JobProfile extends JsonResource
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
            "job_title" => $this->title
        ];
    }
}

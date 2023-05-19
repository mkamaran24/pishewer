<?php

namespace App\Http\Resources\JobList;

use Illuminate\Http\Resources\Json\JsonResource;

class FriendProfile extends JsonResource
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
            'profile_image' => $this->imageprofile
        ];
    }
}

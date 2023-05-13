<?php

namespace App\Http\Resources\ViewProfile;

use Illuminate\Http\Resources\Json\JsonResource;

class UserTranslation extends JsonResource
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
            "username_transalation" => BuyerTranslation::collection($this->usertranslations),
            "profile" => new BuyerProfile($this->profile)
        ];
    }
}

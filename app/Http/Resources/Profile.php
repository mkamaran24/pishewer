<?php

namespace App\Http\Resources;

use App\Http\Resources\Translation\Profile as TranslationProfile;
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
            'nationalid' => $this->nationalid,
            'imageprofile' => $this->imageprofile,
            'city_id' => new City($this->city),
            'profile_translation' => TranslationProfile::collection($this->profiletranslation)
        ];
    }
}

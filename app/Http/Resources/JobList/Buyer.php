<?php

namespace App\Http\Resources\JobList;

use App\Http\Resources\Profile;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Translation\User as TranslationUser;

class Buyer extends JsonResource
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
            // 'email'=>$this->email,
            'translation' => TranslationUser::collection($this->usertranslations),
            'profile_image' => $this->profile->imageprofile ?? null
        ];
    }
}

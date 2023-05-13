<?php

namespace App\Http\Resources\Translation;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'fullname'=>$this->fullname,
            'username'=>$this->username,
            'locale'=>$this->locale
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Http\Resources\ViewProfile\UserTranslation;
use Illuminate\Http\Resources\Json\JsonResource;

class ReplyReview extends JsonResource
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
            "id"=>(string)$this->id,
            "description"=>$this->description,
            "seller"=> new UserTranslation($this->user)
        ];
    }
}

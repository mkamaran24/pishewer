<?php

namespace App\Http\Resources\Translation;

use Illuminate\Http\Resources\Json\JsonResource;

class Subcategory extends JsonResource
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
            'name' => $this->name,
            'locale' => $this->locale
        ];
    }
}

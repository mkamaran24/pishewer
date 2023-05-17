<?php

namespace App\Http\Resources\ViewJob;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Translation\Category as TranslationCategory;

class JobCategory extends JsonResource
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
            'category_translation' => TranslationCategory::collection($this->categorytrans),
        ];
    }
}

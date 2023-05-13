<?php

namespace App\Http\Resources;

use App\Http\Resources\Translation\Subcategory as TranslationSubcategory;
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
            'id' => (string)$this->id,
            'subcategory_translation' => TranslationSubcategory::collection($this->subcategorytrans),
        ];
    }
}

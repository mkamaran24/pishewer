<?php

namespace App\Http\Resources\ViewJob;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Translation\Subcategory as TranslationSubcategory;

class JobSubcategory extends JsonResource
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

<?php

namespace App\Http\Resources;

use App\Http\Resources\Translation\Category as TranslationCategory;
use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => (string)$this->id,
            'category_translation' => TranslationCategory::collection($this->categorytrans),
        ];
    }
}

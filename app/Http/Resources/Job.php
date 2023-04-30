<?php

namespace App\Http\Resources;

use App\Http\Resources\ViewJob\ReviewCollection;
use App\Models\Favorite;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class Job extends JsonResource
{

    public function toArray($request)
    {
        $user = auth('sanctum')->user();

        return [
            'id' => (string)$this->id,
            'status' => $this->status,
            'favs_count' => $this->favorites_count,
            'favorited_by_user' => $user ? $this->favorites->contains('user_id',$user->id) : false,
            'title' => $this->title,
            'image' => Jobimage::collection($this->jobimages),
            'description' => $this->description,
            'keyword' => Keyword::collection($this->keywords),
            'price' => $this->price,
            'completein' => $this->completein,
            'user' => $this->user_id,
            'category' => new Category($this->category),
            // 'subcategory' => new Subcategory($this->subcategory),
            'addons'=>$this->addons,
            'reviews' => new ReviewCollection($this->reviews)
        ];
    }
}
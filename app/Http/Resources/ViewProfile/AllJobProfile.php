<?php

namespace App\Http\Resources\ViewProfile;

use App\Http\Resources\Jobimage;
use App\Http\Resources\Keyword;
use App\Http\Resources\Translation\Category;
use App\Http\Resources\Translation\Job as TranslationJob;
use App\Http\Resources\Translation\Subcategory;
use Illuminate\Http\Resources\Json\JsonResource;

class AllJobProfile extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = auth('sanctum')->user();
        return [
            'id' => (string)$this->id,
            'status' => $this->status,
            'favs_count' => $this->favorites_count,
            'favorited_by_user' => $user ? $this->favorites->contains('user_id', $user->id) : false,
            'job_translation' => TranslationJob::collection($this->jobtrans),
            'user' => $this->user_id,
            'category_translation' => Category::collection($this->category->categorytrans),
            'image' => Jobimage::collection($this->jobimages),
            'keyword' => Keyword::collection($this->keywords),
            // 'addons'=>$this->addons,        
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Http\Resources\Translation\Category;
use App\Http\Resources\Translation\Job as TranslationJob;
use App\Http\Resources\Translation\Subcategory;
use App\Http\Resources\ViewJob\JobCategory;
use App\Http\Resources\ViewJob\JobSubcategory;
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
            'job_translation' => TranslationJob::collection($this->jobtrans),
            'user' => new User($this->user),
            'category' => new JobCategory($this->category),
            'subcategory' => new JobSubcategory($this->subcategory),
            'image' => Jobimage::collection($this->jobimages),
            'keyword' => Keyword::collection($this->keywords),
            'addons'=>$this->addons,
            'reviews' => new ReviewCollection($this->reviews)
        ];
    }
}
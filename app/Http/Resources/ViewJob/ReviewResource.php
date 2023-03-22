<?php

namespace App\Http\Resources\ViewJob;

use App\Http\Resources\ReplyReview;
use App\Http\Resources\ViewProfile\Buyer;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            "service_quality"=>(string)$this->service_quality,
            "commun_followup"=>(string)$this->commun_followup,
            "panctual_delevery"=>(string)$this->panctual_delevery,
            "description"=>$this->description,
            "buyer"=>new Buyer($this->user),
            // "job"=>new JobProfile($this->job),
            "reply"=> new ReplyReview($this->replyreview)
        ];
    }
}

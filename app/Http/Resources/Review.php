<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Review extends JsonResource
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
            "service_quality"=>$this->service_quality,
            "commun_followup"=>$this->commun_followup,
            "panctual_delevery"=>$this->panctual_delevery,
            "description"=>$this->description,
            "buyer_id"=>$this->buyer_id,
            "job_id"=>$this->job_id,
            "reply"=> new ReplyReview($this->replyreview)
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Offer extends JsonResource
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
            'id'=>(string)$this->id,
            'title'=>$this->title,
            'price'=>$this->price,
            'delivery_date'=>$this->delivery_date,
            // 'buyer_status'=>$this->status,
            'offer_state'=>$this->offer_state,
            'seller'=>new User($this->seller),
            'buyer'=>new User($this->buyer),
            'order' => new Order($this->order),
            'job_id'=>$this->job_id
        ];
    }
}

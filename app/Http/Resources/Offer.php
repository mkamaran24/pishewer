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
            'status'=>$this->status,
            'seller_id'=>$this->seller_id,
            'buyer_id'=>$this->buyer_id,
            'job_id'=>$this->job_id
        ];
    }
}

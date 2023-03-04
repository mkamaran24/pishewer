<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobListMessage extends JsonResource
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
            'seller_id'=>$this->seller_id,
            'buyer_id'=>$this->buyer_id,
            'job_id'=>$this->job_id,
            'timestamp'=>$this->created_at
        ];
    }
}

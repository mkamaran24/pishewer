<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
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
            'fastpay_number' => $this->fastpay_number,
            'total_price' => $this->total_price,
            'offer_price' => $this->offer_price,
            'total_addon_price' => $this->total_addon_price,
            'comision_fee' => $this->comision_fee,
            // 'buyer_id' => $this->buyer_id,
            'offer' => new Offer($this->offer),
            'status' => $this->status
        ];
    }
}

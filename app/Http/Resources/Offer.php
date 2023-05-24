<?php

namespace App\Http\Resources;

use App\Models\Order;
use Carbon\Carbon;
use DateTime;
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
        $delivary_date = 1; //input1
        $date = $this->created_at; //input2

        $remaining_t = $delivary_date * 24 * 60 * 60;
        $date_S = strtotime($date);
        $remain = $date_S + $remaining_t;
        $remain_date = date('Y-m-d h:i:s', $remain);

        $now = new DateTime();
        $future_date = new DateTime($remain_date);

        $interval = $future_date->diff($now);
        // $result = $interval->format("%d days, %h hours, %i minutes");
        $result = $interval->format("%d days, %h hours");
        $is_zero = substr($result, 0, 1);

        if ($is_zero == 0) {$result = $interval->format("%h hours");}

        return [
            'id' => (string)$this->id,
            'title' => $this->title,
            'price' => $this->price,
            'created_at' => $this->created_at,
            'payment_status' => Order::where('offer_id', $this->id)->value('status') ? "Paid" : "Unpaid",
            'delivery_periods' => $this->delivery_period,
            'remainin_time' => $result,
            'offer_state' => $this->offer_state,
            'seller' => new User($this->seller),
            'buyer' => new User($this->buyer),
            'offer_addons' => $this->offeraddons
            // 'order' => new Order($this->order),
            // 'job_id'=>$this->job_id
        ];
    }
}

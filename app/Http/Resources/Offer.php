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

        // Create a Carbon instance from the timestamp
        $carbonDate = Carbon::parse($this->created_at);

        // Format the date using the format() method
        $formattedDate = $carbonDate->format('Y-m-d h:i:s');

        $delivary_date = $this->delivery_period + 2; //input1
        $date = $formattedDate; //input2

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

        if ($is_zero == 0) {
            $result = $interval->format("%h hours");
        }

        // // Assuming $deliveryPeriod holds the delivery period in days
        // $deliveryPeriod = 1;

        // // Assuming $createdAt holds the creation time of the offer
        // $createdAt = Carbon::parse('2023-05-23 18:00:00');

        // // Calculate the delivery date by adding the delivery period to the creation time
        // $deliveryDate = $createdAt->addDays($deliveryPeriod);

        // $dct = Carbon::parse($deliveryDate);

        // // Calculate the remaining time by subtracting the current time from the delivery date
        // $remainingTime = Carbon::now()->diff($deliveryDate)->format('%d days, %h hours, %i minutes');

        return [
            'id' => (string)$this->id,
            'title' => $this->title,
            'price' => $this->price,
            // 'deliveryDate' => $dct,
            // 'formattedDate' => $formattedDate,
            // 'carbonDate' => $carbonDate,
            // 'remain_date' => $remain_date,
            // 'interval' => $interval,
            // 'now' => $now,
            // 'future_date' => $future_date,
            // 'created_at' => $formattedDate,
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

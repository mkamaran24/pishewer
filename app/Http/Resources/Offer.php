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
        $expiryDate = Carbon::parse($this->offer_expiry);
        // $remainingDays = $expiryDate->diffForHumans(Carbon::now());
        $remainingDays = $expiryDate->diffInDays(Carbon::now());
        $remainingHours = $expiryDate->diffInHours(Carbon::now()) % 24;

        return [
            'id' => (string)$this->id,
            'title' => $this->title,
            'price' => $this->price,
            'offer_code' => $this->offer_code,
            'payment_status' => Order::where('offer_id', $this->id)->value('status') ? "Paid" : "Unpaid",
            'delivery_periods' => $this->delivery_period,
            'now' => Carbon::now(),
            'offer_expiry' => Carbon::parse($this->offer_expiry),
            'created_at' => $this->created_at,
            'remainin_time' => $remainingDays . ' Days - ' . $remainingHours . ' Hours',
            'offer_state' => $this->offer_state,
            'seller' => new User($this->seller),
            'buyer' => new User($this->buyer),
            'offer_addons' => $this->offeraddons,
            'Attachments' => $this->attachments
            // 'order' => new Order($this->order),
            // 'job_id'=>$this->job_id
        ];
    }
}

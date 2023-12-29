<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class Invoice extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $remainingDays = "0";
        $expiryDate = null;
        $isBlocked = DB::table('invoices')->select('status')->where('status', 'Blocked')->where('id', $this->id)->exists();
        if ($isBlocked) {
            $expiryDate = Carbon::parse($this->offer->offer_expiry)->addDays(14);
            $remainingDays = $expiryDate->diffInDays(Carbon::now());
        }

        return [
            'id' => $this->id,
            'offer_code' => $this->offer->offer_code,
            'offer_expiry' => $this->offer->offer_expiry,
            'remaining_date' => $expiryDate,
            'block_remaining_days' => $remainingDays,
            'seller' => $this->seller_id,
            'amount' => $this->offer_amount,
            'status' => $this->status
        ];
    }
}

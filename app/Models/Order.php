<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['fastpay_number','total_price','offer_price','total_addon_price','comision_fee','buyer_id','offer_id','status'];

    public function user()
    {
        return $this->belongsTo(User::class,'buyer_id');
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class,'offer_id');
    }

}

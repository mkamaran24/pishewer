<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'price',
        'offer_code',
        'delivery_period',
        'seller_id',
        'buyer_id',
        'offer_state',
        'job_id'
    ];

    public function seller()
    {
        return $this->belongsTo(User::class,'seller_id');
    }
    public function buyer()
    {
        return $this->belongsTo(User::class,'buyer_id');
    }

    public function job()
    {
        return $this->belongsTo(Jobs::class,'job_id');
    }

    public function offeraddons()
    {
        return $this->hasMany(OfferAddon::class,'offer_id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class,'offer_id');
    }
    

    // public function order()
    // {
    //     return $this->hasOne(Order::class.'offer_id');
    // }

}

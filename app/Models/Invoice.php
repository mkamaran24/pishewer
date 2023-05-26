<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['offer_id','seller_id','offer_amount','status'];

    public function user()
    {
        return $this->belongsTo(User::class,'seller_id');
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class,'offer_id');
    }

}

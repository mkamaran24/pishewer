<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferAddon extends Model
{
    use HasFactory;

    protected $fillable = ['title','price','order_id'];

    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }
}

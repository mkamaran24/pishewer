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
        'delivery_date',
        'status',
        'seller_id',
        'buyer_id',
        'job_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'seller_id');
    }

    public function job()
    {
        return $this->belongsTo(Jobs::class,'job_id');
    }

}

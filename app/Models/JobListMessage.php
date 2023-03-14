<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobListMessage extends Model
{
    use HasFactory;

    protected $fillable = ['seller_id','buyer_id','job_id'];

    public function job()
    {
        return $this->belongsTo(Jobs::class,'job_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class,'seller_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class,'buyer_id');
    }

}

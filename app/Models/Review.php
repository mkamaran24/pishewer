<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['service_quality','commun_followup','panctual_delevery','description','user_id','job_id','offer_id'];

    // public $timestamps = false;

    public function replyreview()
    {
        return $this->hasOne(ReplyReview::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function job()
    {
        return $this->belongsTo(Jobs::class,'job_id');
    }

}

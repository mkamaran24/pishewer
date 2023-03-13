<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['service_quality','commun_followup','panctual_delevery','description','buyer_id','job_id'];

    // public $timestamps = false;

    public function replyreview()
    {
        return $this->hasOne(ReplyReview::class);
    }

}

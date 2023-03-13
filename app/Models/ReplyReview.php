<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReplyReview extends Model
{
    use HasFactory;

    protected $fillable = ['description','seller_id','review_id'];

    // public $timestamps = false;

    public function review()
    {
        return $this->belongsTo(Review::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendList extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','friend_id','ftc_code'];

    public function user()
    {
        return $this->belongsTo(User::class,'friend_id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'skills',
        'langs',
        'certification',
        'nationalid',
        'imageprofile',
        'city_id',
        'age',
        'gender',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function city(){
        return $this->belongsTo(City::class,'city_id');
    }

}



<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'nationalid',
        'imageprofile',
        'city_id',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function city(){
        return $this->belongsTo(City::class,'city_id');
    }

    public function profiletranslation()
    {
        return $this->hasMany(ProfileTranslation::class,'profile_id')->where('locale',App::getLocale());
    }

}



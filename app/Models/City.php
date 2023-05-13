<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class City extends Model
{
    use HasFactory;

   
    public function citytranslations()
    {
        return $this->hasMany(CityTranslation::class,'city_id')->where('locale',App::getLocale());
    }

}

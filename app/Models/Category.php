<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Category extends Model
{
    use HasFactory;

    public function categorytrans()
    {
        return $this->hasMany(CategoryTrans::class,'categ_id')->where('locale', App::getLocale());
    }

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class,'categ_id');
    }

}

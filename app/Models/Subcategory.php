<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = ['categ_id']; 

    public function subcategorytrans()
    {
        return $this->hasMany(SubcategoryTrans::class,'subcateg_id')->where('locale',App::getLocale());
    }

}

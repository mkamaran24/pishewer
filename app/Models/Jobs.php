<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'description',
        'keyword',
        'price',
        'completein',
        'user_id',
        'categ_id',
        'subcateg_id'
    ];


    public function category()
    {
        return $this->belongsTo(Category::class, 'categ_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcateg_id');
    }

}

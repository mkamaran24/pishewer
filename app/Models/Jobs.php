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

    // protected $casts = [
    //     'keyword' => 'array',
    // ];


    public function category()
    {
        return $this->belongsTo(Category::class, 'categ_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcateg_id');
    }

    public function addons()
    {
        return $this->hasMany(Addons::class, 'job_id');
    }

    public function jobimages()
    {
        return $this->hasMany(Jobimage::class, 'job_id');
    }

    public function keywords()
    {
        return $this->hasMany(Keyword::class, 'job_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'job_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class,'job_id');
    }

    // public function favorites()
    // {
    //     return $this->belongsToMany(User::class, 'job_favorites', 'job_id', 'user_id');
    // }
}

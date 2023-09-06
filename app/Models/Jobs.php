<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Jobs extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'sold', 'categ_id', 'subcateg_id', 'user_id'];

    // protected $casts = [
    //     'keyword' => 'array',
    // ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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
        return $this->hasMany(Favorite::class, 'job_id');
    }

    public function jobtrans()
    {
        $locale = App::getLocale();
        return $this->hasMany(JobTrans::class, 'job_id')->where('locale', $locale);
    }

    // public function category()
    // {
    //     return $this->belongsTo(CategoryTrans::class, 'categ_id')
    //         ->where('locale', 'en');
    // }

    public function category()
    {
        return $this->belongsTo(Category::class, 'categ_id');
    }
}

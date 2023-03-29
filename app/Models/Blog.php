<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = ['image','title','body','user_id','blog_category_id'];

    public function blogcategory()
    {
        return $this->belongsTo(BlogCategory::class,'blog_category_id');
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class)->whereNull('parent_id');
    }

}

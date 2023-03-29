<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    use HasFactory;

    protected $fillable = ['body','blog_id','user_id','parent_id'];

    public function replies()
    {
        return $this->hasMany(BlogComment::class, 'parent_id');
    }

}

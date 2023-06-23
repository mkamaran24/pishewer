<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTrans extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'completein',
        'user_id',
        'categ_id',
        'subcateg_id',
        'job_id',
        'locale'
    ];

    public function job()
    {
        return $this->belongsTo(Jobs::class,'job_id');
    }

}

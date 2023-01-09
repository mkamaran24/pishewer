<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addons extends Model
{
    use HasFactory;

    protected $fillable = ['title','price','job_id'];


    public function job(){
        return $this->belongsTo(Jobs::class,'job_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobimage extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','job_id'];

    public function job(){
        return $this->belongsTo(Jobs::class,'job_id');
    }

}

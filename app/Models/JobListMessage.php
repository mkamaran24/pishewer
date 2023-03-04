<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobListMessage extends Model
{
    use HasFactory;

    protected $fillable = ['seller_id','buyer_id','job_id'];

    
}

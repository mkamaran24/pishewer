<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryTrans extends Model
{
    use HasFactory;

    protected $fillable = ['name','description','popular','locale','categ_id'];

}

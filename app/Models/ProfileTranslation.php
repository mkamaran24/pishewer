<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'skills',
        'langs',
        'certification',
        'age',
        'gender',
        'profile_id'
    ];
}

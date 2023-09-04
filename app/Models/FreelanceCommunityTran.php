<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelanceCommunityTran extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'story', 'locale', 'freelance_community_id'];
}

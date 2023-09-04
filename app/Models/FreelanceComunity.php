<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class FreelanceComunity extends Model
{
    use HasFactory;

    protected $fillable = ['image'];


    public function freelancecommunitytrans()
    {
        return $this->hasMany(FreelanceCommunityTran::class, 'freelance_community_id')->where('locale', App::getLocale());
    }
}

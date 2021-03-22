<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CanIJoinCommunity extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'bell_id',
        'user_id',
        'community_id',
    ];

}

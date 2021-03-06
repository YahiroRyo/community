<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IsJoiningCommunity extends Model
{
    public $timestamps  = false;
    protected $fillable = [
        'user_id',
        'community_id',
    ];
}

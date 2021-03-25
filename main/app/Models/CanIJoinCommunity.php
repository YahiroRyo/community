<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Community;
use App\Models\UserInfo;

class CanIJoinCommunity extends Model
{
    public $timestamps  = false;
    protected $fillable = [
        'bell_id',
        'user_id',
        'community_id',
    ];
    public function community() {
        return $this->hasOne(Community::class, 'id', 'community_id');
    }
    public function userInfo() {
        return $this->hasOne(UserInfo::class, 'user_id', 'user_id');
    }
}

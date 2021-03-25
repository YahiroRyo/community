<?php

namespace App\Models;

use Kreait\Firebase\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserInfo;
use App\Models\Great;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
        'content',
    ];
    public function userInfo() {
        return $this->hasOne(UserInfo::class, 'user_id', 'user_id');
    }
    public function isGreatPost() {
        return $this->hasMany(Great::class, 'post_id', 'id');
    }
    public function greatPostNum() {
        return $this->hasMany(Great::class, 'post_id', 'id');
    }
    public function responceNum() {
        return $this->hasMany(Post::class, 'post_id', 'id');
    }
}

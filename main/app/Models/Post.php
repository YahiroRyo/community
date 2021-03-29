<?php

namespace App\Models;

use Kreait\Firebase\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserInfo;
use App\Models\Great;

class Post extends Model
***REMOVED***
    protected $fillable = [
        'user_id',
        'post_id',
        'community_id',
        'content',
    ];
    public function userInfo() ***REMOVED***
        return $this->hasOne(UserInfo::class, 'user_id', 'user_id');
    ***REMOVED***
    public function isGreatPost() ***REMOVED***
        return $this->hasMany(Great::class, 'post_id', 'id');
    ***REMOVED***
    public function greatPostNum() ***REMOVED***
        return $this->hasMany(Great::class, 'post_id', 'id');
    ***REMOVED***
    public function responceNum() ***REMOVED***
        return $this->hasMany(Post::class, 'post_id', 'id');
    ***REMOVED***
***REMOVED***

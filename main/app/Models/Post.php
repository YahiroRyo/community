<?php

namespace App\Models;

use Kreait\Firebase\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserInfo;

class Post extends Model
***REMOVED***
    protected $fillable = [
        'user_id',
        'post_id',
        'content',
    ];
    public function userInfo() ***REMOVED***
        return $this->hasOne(UserInfo::class, 'user_id', 'user_id');
    ***REMOVED***
***REMOVED***

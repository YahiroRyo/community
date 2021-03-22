<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\IsJoiningCommunity;
use App\Models\CanIJoinCommunity;

class Community extends Model
***REMOVED***
    protected $fillable = [
        'user_id',
        'name',
        'description',
    ];
    public function isJoiningCommunity() ***REMOVED***
        return $this->hasOne(IsJoiningCommunity::class);
    ***REMOVED***
    public function canIJoinCommunity() ***REMOVED***
        return $this->hasOne(CanIJoinCommunity::class);
    ***REMOVED***
***REMOVED***

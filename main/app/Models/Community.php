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
    public static function rules() ***REMOVED***
        return [
            'name' => [
                'required',
                'max:50',
            ],
            'description' => [
                'required',
                'max:500',
            ],
        ];
    ***REMOVED***
    public function isJoiningCommunity() ***REMOVED***
        return $this->hasOne(IsJoiningCommunity::class);
    ***REMOVED***
    public function canIJoinCommunity() ***REMOVED***
        return $this->hasOne(CanIJoinCommunity::class);
    ***REMOVED***
    
***REMOVED***

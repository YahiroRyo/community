<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\IsJoiningCommunity;
use App\Models\CanIJoinCommunity;

class Community extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
    ];
    public static function rules() {
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
    }
    public function isJoiningCommunity() {
        return $this->hasOne(IsJoiningCommunity::class);
    }
    public function canIJoinCommunity() {
        return $this->hasOne(CanIJoinCommunity::class);
    }
    
}

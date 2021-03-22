<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
***REMOVED***
    public $timestamps = false;
    public $primaryKey = 'user_id';
    protected $fillable = [
        'user_id',
        'name',
        'user_name',
        'intro',
    ];
***REMOVED***

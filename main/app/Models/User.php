<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
***REMOVED***
    protected $fillable = [
        'uid',
    ];
    public static function rules() ***REMOVED***
        return [
            'name' => [
                'required',
                'max:30',
            ],
            'userName' => [
                'required',
                'regex:/^[a-zA-Z0-9-]+$/',
                'max:30',
            ],
        ];
    ***REMOVED***
***REMOVED***

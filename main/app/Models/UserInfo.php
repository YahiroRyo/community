<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
***REMOVED***
    public $timestamps  = false;
    public $primaryKey  = 'user_id';
    protected $fillable = [
        'user_id',
        'name',
        'user_name',
        'intro',
    ];
    public static function rules() ***REMOVED***
        return [
            'name' => [
                'required',
                'max:30',
            ],
            'userName' => [
                'required',
                'regex:regex:/^[a-zA-Z0-9-]+$/',
                'max:30',
            ],
            'intro' => [
                'required',
                'max:200',
            ],
        ];
    ***REMOVED***
***REMOVED***

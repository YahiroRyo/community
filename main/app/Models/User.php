<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'uid',
    ];
    public static function rules() {
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
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    public $timestamps  = false;
    public $primaryKey  = 'user_id';
    protected $fillable = [
        'user_id',
        'name',
        'user_name',
        'intro',
    ];
    public static function rules() {
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
            'intro' => [
                'max:200',
            ],
        ];
    }
}

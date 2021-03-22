<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bell extends Model
{
    /* ---------------typeに対しての説明--------------- */
    // type1: コミュニティへの申請の通知
    protected $fillable = [
        'type',
    ];
}

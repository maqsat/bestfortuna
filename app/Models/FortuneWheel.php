<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FortuneWheel extends Model
{
    protected $table='fortune_wheel';

    protected $fillable = [
        'user_id','success',
    ];
}

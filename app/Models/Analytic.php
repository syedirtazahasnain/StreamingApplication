<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Analytic extends Model
{
    use SoftDeletes;
    protected $table = 'analytics';
    protected $fillable = [
        'user_id',
        'video_id',
        'event',
    ];
}

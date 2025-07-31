<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;
    protected $table = 'messages';
    protected $fillable = [
        'user_id',
        'video_id',
        'type',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function canBeDeletedBy(User $user)
    {
        if ($user->user_role === 'admin') {
            return true;
        }
        if (
            $this->video &&
            $this->video->channel &&
            $this->video->channel->user_id === $user->id
        ) {
            return true;
        }
        return false;
    }
}

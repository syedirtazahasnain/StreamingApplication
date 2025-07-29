<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use SoftDeletes;
    protected $table = 'videos';
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'url',
        'thumbnail',
        'view_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function messagesWithUser()
    {
        return $this->hasMany(Message::class)
            ->select('id', 'user_id', 'video_id', 'type', 'content', 'created_at')
            ->with(['user:id,name']);
    }
}

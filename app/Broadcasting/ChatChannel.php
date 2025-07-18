<?php

namespace App\Broadcasting;

use App\Models\User;
use App\Models\Video;

class ChatChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user, $videoId, $type)
    {
        $video = Video::findOrFail($videoId);
        return $user->id === $video->user_id ||
               in_array($user->user_role, ['admin', 'mod', "{$video->user_id}_mod"]) ||
               $type === 'chat' || $type === 'comment';
    }
}

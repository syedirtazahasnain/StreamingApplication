<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\User;
use App\Models\Video;
use App\Models\Subscription;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile($id)
    {
        $user = User::select('id', 'name', 'community', 'tag')->findOrFail($id);
        return response()->json($user);
    }

    public function follow(Request $request, $videoId)
    {
        $video = Video::findOrFail($videoId);

        $subscription = Subscription::create([
            'user_id' => auth()->id(),
            'channel_id' => $video->user_id,
        ]);

        return response()->json($subscription, 201);
    }

    public function like(Request $request, $videoId)
    {
        $like = Like::create([
            'user_id' => auth()->id(),
            'video_id' => $videoId,
        ]);

        return response()->json($like, 201);
    }
}

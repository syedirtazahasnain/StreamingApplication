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
        try {
            $user = User::select('id', 'name','email', 'community', 'tag', 'profile_picture', 'user_role')->findOrFail($id);
            return success_res(200, 'Profile retrieved successfully', $user);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return error_res(403, 'User not found');
        } catch (\Exception $e) {
            return error_res(403, 'Failed to retrieve profile');
        }
    }

    public function follow(Request $request, $videoId)
    {
        try {
            $video = Video::findOrFail($videoId);
            $existing_subscription = Subscription::where('user_id', auth()->id())
                ->where('channel_id', $video->user_id)
                ->first();

            if ($existing_subscription) {
                return error_res(403, 'Already following this channel');
            }

            $subscription = Subscription::create([
                'user_id' => auth()->id(),
                'channel_id' => $video->user_id,
            ]);

            return success_res(200, 'Subscription created successfully', $subscription);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return error_res(403, 'Video not found');
        } catch (\Exception $e) {
            return error_res(403, 'Failed to create subscription');
        }
    }

    public function like(Request $request, $videoId)
    {
        try {
            $existing_like = Like::where('user_id', auth()->id())
                ->where('video_id', $videoId)
                ->first();

            if ($existing_like) {
                return error_res(403, 'Already liked this video');
            }

            $like = Like::create([
                'user_id' => auth()->id(),
                'video_id' => $videoId,
            ]);

            return success_res(200, 'Like created successfully', $like);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return error_res(403, 'Video not found');
        } catch (\Exception $e) {
            return error_res(403, 'Failed to create like');
        }
    }
}

<?php
namespace App\Services;

use App\Models\Video;
use Illuminate\Support\Facades\Http;

class VideoService
{
    public function search($query)
    {
        return Video::where('title', 'like', "%$query%")
            ->orWhereHas('user', function ($q) use ($query) {
                $q->where('name', 'like', "%$query%");
            })
            ->with('user')
            ->get();
    }

    public function isValidStream($url)
    {
        try {
            $response = Http::head($url);
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getRecommendations($video)
    {
        return Video::where('type', $video->type)
            ->where('id', '!=', $video->id)
            ->inRandomOrder()
            ->take(5)
            ->get();
    }
}

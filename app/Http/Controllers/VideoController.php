<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use App\Services\VideoService;
use App\Http\Requests\VideoSearchRequest;
use App\Exceptions\InvalidStreamException;

class VideoController extends Controller
{
    protected $videoService;

    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
    }

    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $videos = Video::with('user')->paginate($perPage);

        return response()->json($videos);
    }

    public function search(VideoSearchRequest $request)
    {
        $videos = $this->videoService->search($request->query('q'));
        return response()->json($videos);
    }

    public function show($id)
    {
        $video = Video::with('user')->findOrFail($id);

        if (!$this->videoService->isValidStream($video->url)) {
            throw new InvalidStreamException('Invalid stream URL');
        }

        return response()->json($video);
    }

    public function recommendations($id)
    {
        $video = Video::findOrFail($id);
        $recommendations = $this->videoService->getRecommendations($video);
        return response()->json($recommendations);
    }
}

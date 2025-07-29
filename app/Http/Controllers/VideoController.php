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
        try {
            $per_page = $request->query('per_page', 10);
            $videos = Video::with('user')
                ->orderBy('created_at', 'desc')
                ->paginate($per_page);

            return success_res(200, 'Videos retrieved successfully', $videos);
        } catch (\Exception $e) {
            return error_res(403, 'Failed to retrieve videos');
        }
    }

    public function search(VideoSearchRequest $request)
    {
        try {
            $videos = $this->videoService->search($request->query('q'));
            return success_res(200, 'Search results retrieved successfully', $videos);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return error_res(403, 'Validation failed', $e->errors());
        } catch (\Exception $e) {
            return error_res(403, 'Search failed');
        }
    }

    public function show($id)
    {
        try {
            $video = Video::select('id','user_id','type','title','url','thumbnail','view_count','created_at')->with('user:id,name,email,user_role,profile_picture','messagesWithUser')->findOrFail($id);

            if (!$this->videoService->isValidStream($video->url)) {
                return error_res(403, 'Invalid stream URL');
            }

            return success_res(200, 'Video retrieved successfully', $video);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return error_res(403, 'Video not found');
        } catch (\Exception $e) {
            return error_res(403, 'Failed to retrieve video');
        }
    }

    public function recommendations($id)
    {
        try {
            $video = Video::findOrFail($id);
            $recommendations = $this->videoService->getRecommendations($video);
            return success_res(200, 'Recommendations retrieved successfully', $recommendations);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return error_res(403, 'Video not found');
        } catch (\Exception $e) {
            return error_res(403, 'Failed to get recommendations');
        }
    }
}

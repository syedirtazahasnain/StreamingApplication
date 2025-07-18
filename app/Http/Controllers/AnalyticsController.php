<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnalyticsService;

class AnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'video_id' => 'required|exists:videos,id',
            'event' => 'required|string|in:playback_start,watched_30s,watched_60s',
        ]);

        $this->analyticsService->track($validated['video_id'], $validated['event']);

        return response()->json(['message' => 'Analytics recorded']);
    }
}

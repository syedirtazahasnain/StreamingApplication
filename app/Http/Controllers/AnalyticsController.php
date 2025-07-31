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
        try {
            $validated = $request->validate([
                'video_id' => 'required|exists:videos,id',
                'event' => 'required|string|in:playback_start,watched_30s,watched_60s',
            ]);

            $this->analyticsService->track($validated['video_id'], $validated['event']);
            return success_res(200, 'Analytics recorded');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return error_res(403, 'Validation failed', $e->errors());
        } catch (\Exception $e) {
            return error_res(403, 'Failed to record analytics');
        }
    }
}

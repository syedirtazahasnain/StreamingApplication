<?php
namespace App\Services;

use App\Models\Analytic;

class AnalyticsService
{
    public function track($videoId, $event)
    {
        Analytic::create([
            'user_id' => auth()->id(),
            'video_id' => $videoId,
            'event' => $event,
        ]);
    }
}

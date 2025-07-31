<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Video;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = auth()->user();
        $video = Video::findOrFail($request->videoId);

        $allowed = in_array($user->user_role, $roles) ||
                   $user->user_role === "{$video->user_id}_mod" ||
                   $user->id === $video->user_id;

        if (!$allowed) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}

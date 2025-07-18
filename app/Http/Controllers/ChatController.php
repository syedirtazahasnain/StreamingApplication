<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Http\Requests\MessageRequest;

class ChatController extends Controller
{
     public function store(MessageRequest $request, $videoId)
    {
        $message = Message::create([
            'user_id' => auth()->id(),
            'video_id' => $videoId,
            'type' => $request->type, // chat or comment
            'content' => $request->content,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message, 201);
    }

    public function destroy($videoId, $messageId)
    {
        $message = Message::where('video_id', $videoId)->findOrFail($messageId);
        $message->delete();

        broadcast(new MessageSent($message, true))->toOthers();

        return response()->json(['message' => 'Message deleted']);
    }
}

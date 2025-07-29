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
        try {
            $message = Message::create([
                'user_id' => auth()->id(),
                'video_id' => $videoId,
                'type' => $request->type,
                'content' => $request->content,
            ]);
            broadcast(new MessageSent($message))->toOthers();
            $message->name = auth()->user()->name;
            return success_res(200, 'Message sent successfully', $message);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return error_res(403, 'Validation failed', $e->errors());
        } catch (\Exception $e) {
            return error_res(403, 'Failed to send message');
        }
    }

    public function destroy($videoId, $messageId)
    {
        try {
            $message = Message::where('video_id', $videoId)
                ->findOrFail($messageId);
            $message->delete();
            broadcast(new MessageSent($message, true))->toOthers();
            return success_res(200, 'Message deleted');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return error_res(404, 'Message not found');
        } catch (\Exception $e) {
            return error_res(500, 'Failed to delete message');
        }
    }
}

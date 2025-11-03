<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Conversation;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function send(Request $request)
    {
        $message = Message::create([
            'conversation_id' => $request->conversation_id,
            'sender_id' => Auth::id(),
            'body' => $request->body,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }

    public function index($conversationId)
    {
        return Message::where('conversation_id', $conversationId)->get();
    }
}

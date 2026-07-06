<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function individual(Request $request)
    {
        $user = Auth::user();
        
        // List all other users
        $users = User::where('id', '!=', $user->id)->get();

        $selectedUser = null;
        $messages = collect();

        if ($request->has('user_id')) {
            $selectedUser = User::findOrFail($request->user_id);
            $messages = Chat::with('sender')
                ->where(function ($query) use ($user, $selectedUser) {
                    $query->where('sender_id', $user->id)
                          ->where('receiver_id', $selectedUser->id);
                })
                ->orWhere(function ($query) use ($user, $selectedUser) {
                    $query->where('sender_id', $selectedUser->id)
                          ->where('receiver_id', $user->id);
                })
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('chat.individual', compact('users', 'selectedUser', 'messages'));
    }

    public function sendIndividual(Request $request, User $receiver)
    {
        $request->validate([
            'message' => ['required', 'string'],
        ]);

        Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiver->id,
            'message' => $request->message,
            'is_group' => false,
        ]);

        return redirect()->route('chat.individual', ['user_id' => $receiver->id]);
    }

    public function group()
    {
        $user = Auth::user();
        if ($user->isClient()) {
            abort(403, 'Unauthorized.');
        }

        $messages = Chat::with('sender')
            ->where('is_group', true)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('chat.group', compact('messages'));
    }

    public function sendGroup(Request $request)
    {
        $user = Auth::user();
        if ($user->isClient()) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'message' => ['required', 'string'],
        ]);

        Chat::create([
            'sender_id' => $user->id,
            'receiver_id' => null,
            'message' => $request->message,
            'is_group' => true,
        ]);

        return redirect()->route('chat.group');
    }

    public function update(Request $request, Chat $chat)
    {
        // Only sender can edit
        if ($chat->sender_id !== Auth::id()) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'message' => ['required', 'string'],
        ]);

        $chat->update([
            'message' => $request->message,
        ]);

        // Redirect back to the appropriate chat
        if ($chat->is_group) {
            return redirect()->route('chat.group');
        }

        $otherUserId = $chat->sender_id === Auth::id() ? $chat->receiver_id : $chat->sender_id;
        return redirect()->route('chat.individual', ['user_id' => $otherUserId]);
    }

    public function destroy(Chat $chat)
    {
        // Only sender can delete
        if ($chat->sender_id !== Auth::id()) {
            abort(403, 'Unauthorized.');
        }

        $isGroup = $chat->is_group;
        $otherUserId = $chat->receiver_id;

        $chat->delete();

        if ($isGroup) {
            return redirect()->route('chat.group');
        }

        return redirect()->route('chat.individual', ['user_id' => $otherUserId]);
    }
}

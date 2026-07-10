<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\GroupChat;
use App\Models\GroupChatMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'sender_id'  => Auth::id(),
            'receiver_id' => $receiver->id,
            'message'    => $request->message,
            'is_group'   => false,
        ]);

        return redirect()->route('chat.individual', ['user_id' => $receiver->id]);
    }

    // ─── GROUP CHATS ────────────────────────────────────────────────────────

    /**
     * List all group chats the current user is a member of.
     * Also ensure the main group exists and the user is a member.
     */
    public function groups()
    {
        $user = Auth::user();

        // Ensure the main group exists
        $this->ensureMainGroupExists();

        // Get all groups this user is a member of
        $groups = GroupChat::whereHas('members', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('creator')->get();

        return view('chat.groups', compact('groups'));
    }

    public function createGroup()
    {
        $workers = User::where('role', 'worker')->get();
        $clients = User::where('role', 'client')->get();
        $admins  = User::where('role', 'admin')->get();

        return view('chat.create-group', compact('workers', 'clients', 'admins'));
    }

    public function storeGroup(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'members'    => ['nullable', 'array'],
            'members.*'  => ['exists:users,id'],
            'avatar'     => ['nullable', 'image', 'max:2048'],
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('group-avatars', 'public');
        }

        $group = GroupChat::create([
            'name'       => $request->name,
            'avatar'     => $avatarPath,
            'created_by' => $user->id,
            'is_main'    => false,
        ]);

        // Add admin (creator) as member
        $group->users()->attach($user->id);

        // Add selected members
        if ($request->members) {
            foreach ($request->members as $memberId) {
                if ($memberId != $user->id) {
                    $group->users()->syncWithoutDetaching([$memberId]);
                }
            }
        }

        return redirect()->route('chat.group.show', $group->id)
                         ->with('success', 'Grup baru berhasil dibuat.');
    }

    public function showGroup(GroupChat $group)
    {
        $user = Auth::user();

        // Check membership
        $isMember = $group->users()->where('user_id', $user->id)->exists();
        if (!$isMember) {
            abort(403, 'Anda bukan anggota grup ini.');
        }

        $messages = Chat::with('sender')
            ->where('group_chat_id', $group->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $members = $group->users()->get();

        return view('chat.show-group', compact('group', 'messages', 'members'));
    }

    public function sendGroup(Request $request, GroupChat $group)
    {
        $user = Auth::user();

        $isMember = $group->users()->where('user_id', $user->id)->exists();
        if (!$isMember) {
            abort(403, 'Anda bukan anggota grup ini.');
        }

        $request->validate([
            'message' => ['required', 'string'],
        ]);

        Chat::create([
            'sender_id'    => $user->id,
            'receiver_id'  => null,
            'group_chat_id' => $group->id,
            'message'      => $request->message,
            'is_group'     => true,
        ]);

        return redirect()->route('chat.group.show', $group->id);
    }

    public function editGroup(GroupChat $group)
    {
        $workers = User::where('role', 'worker')->get();
        $clients = User::where('role', 'client')->get();
        $admins  = User::where('role', 'admin')->get();
        $currentMemberIds = $group->users()->pluck('users.id')->toArray();

        return view('chat.edit-group', compact('group', 'workers', 'clients', 'admins', 'currentMemberIds'));
    }

    public function updateGroup(Request $request, GroupChat $group)
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'members'    => ['nullable', 'array'],
            'members.*'  => ['exists:users,id'],
            'avatar'     => ['nullable', 'image', 'max:2048'],
        ]);

        $data = ['name' => $request->name];

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($group->avatar) {
                Storage::disk('public')->delete($group->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('group-avatars', 'public');
        }

        $group->update($data);

        // Sync members (preserve the admin creator always)
        $adminId = Auth::id();
        $membersToSync = collect($request->members ?? []);
        if (!$membersToSync->contains($adminId)) {
            $membersToSync->push($adminId);
        }

        // For the main group, don't allow membership changes
        if (!$group->is_main) {
            $group->users()->sync($membersToSync->toArray());
        }

        return redirect()->route('chat.group.show', $group->id)
                         ->with('success', 'Grup berhasil diperbarui.');
    }

    public function destroyGroup(GroupChat $group)
    {
        if ($group->is_main) {
            return back()->withErrors(['error' => 'Grup utama tidak dapat dihapus.']);
        }

        // Delete avatar if exists
        if ($group->avatar) {
            Storage::disk('public')->delete($group->avatar);
        }

        $group->delete();

        return redirect()->route('chat.group')->with('success', 'Grup berhasil dihapus.');
    }

    // ─── DIRECT CHAT EDIT/DELETE ─────────────────────────────────────────────

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
        if ($chat->group_chat_id) {
            return redirect()->route('chat.group.show', $chat->group_chat_id);
        }

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
        $groupChatId = $chat->group_chat_id;
        $otherUserId = $chat->receiver_id;

        $chat->delete();

        if ($groupChatId) {
            return redirect()->route('chat.group.show', $groupChatId);
        }

        if ($isGroup) {
            return redirect()->route('chat.group');
        }

        return redirect()->route('chat.individual', ['user_id' => $otherUserId]);
    }

    // ─── HELPERS ─────────────────────────────────────────────────────────────

    /**
     * Ensure the "Main Group" exists with all users as members.
     */
    private function ensureMainGroupExists(): void
    {
        $mainGroup = GroupChat::where('is_main', true)->first();

        if (!$mainGroup) {
            // Find or create the first admin to be the owner
            $admin = User::where('role', 'admin')->first();
            if (!$admin) {
                return;
            }

            $mainGroup = GroupChat::create([
                'name'       => 'Main Group',
                'avatar'     => null,
                'created_by' => $admin->id,
                'is_main'    => true,
            ]);
        }

        // Sync ALL users into the main group
        $allUserIds = User::pluck('id')->toArray();
        $mainGroup->users()->syncWithoutDetaching($allUserIds);
    }
}

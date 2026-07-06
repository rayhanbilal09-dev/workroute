@extends('layouts.app')

@section('content')
<div style="margin-bottom: 20px;">
    <h1>Group Chat</h1>
    <p>Collective team channel (Admins & Workers only). Clients do not have access here.</p>
</div>

<div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; display: flex; flex-direction: column; min-height: 500px; max-width: 800px; margin: 0 auto;">
    
    <!-- Chat Header -->
    <div style="padding-bottom: 15px; border-bottom: 1px solid #e0e0e0; margin-bottom: 15px;">
        <h3 style="margin: 0; font-size: 18px;"># Internal Discussion Channel</h3>
        <small style="color: #666;">All workers and admins share this space.</small>
    </div>

    <!-- Message Window -->
    <div style="flex-grow: 1; border: 1px solid #eee; border-radius: 4px; background-color: #fafafa; padding: 15px; overflow-y: auto; display: flex; flex-direction: column; gap: 12px; max-height: 350px;">
        @if($messages->isEmpty())
            <p style="color: #aaa; text-align: center; margin: auto;">No messages in group chat yet. Start the conversation!</p>
        @else
            @foreach($messages as $msg)
                <div style="max-width: 75%; padding: 10px; border-radius: 6px; line-height: 1.4; font-size: 14px; position: relative;
                    align-self: {{ $msg->sender_id === auth()->id() ? 'flex-end' : 'flex-start' }};
                    background-color: {{ $msg->sender_id === auth()->id() ? '#d1e7dd' : '#f8f9fa' }};
                    border: 1px solid {{ $msg->sender_id === auth()->id() ? '#badbcc' : '#e2e3e5' }};
                    color: {{ $msg->sender_id === auth()->id() ? '#0f5132' : '#383d41' }};">
                    
                    <div style="font-weight: bold; font-size: 11px; margin-bottom: 3px; display: flex; justify-content: space-between; gap: 10px;">
                        <span style="color: #333;">{{ $msg->sender->name }}</span>
                        <span style="color: #888; font-size: 9px; font-weight: normal;">({{ ucfirst($msg->sender->role) }})</span>
                    </div>
                    
                    <div style="white-space: pre-wrap;">{{ $msg->message }}</div>
                    
                    <div style="font-size: 9px; color: #888; margin-top: 5px;">
                        {{ $msg->created_at->format('d M H:i') }}

                        @if($msg->sender_id === auth()->id())
                            <span style="margin-left: 6px;">
                                <!-- Edit -->
                                <details style="display: inline-block;">
                                    <summary style="cursor: pointer; font-size: 10px; color: #007bff; list-style: none; display: inline;">✎</summary>
                                    <div style="margin-top: 6px; padding: 8px; background: #fff; border: 1px solid #ccc; border-radius: 4px; min-width: 300px;">
                                        <form action="{{ route('chat.update', $msg->id) }}" method="POST" style="display: flex; gap: 5px;">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="message" value="{{ $msg->message }}" style="flex: 1; padding: 6px; border: 1px solid #ccc; border-radius: 3px; font-size: 13px;" required>
                                            <button type="submit" style="background: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 3px; font-size: 12px; cursor: pointer;">Save</button>
                                        </form>
                                    </div>
                                </details>
                                
                                <!-- Delete -->
                                <form action="{{ route('chat.destroy', $msg->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus pesan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #dc3545; font-size: 10px; cursor: pointer; padding: 0; margin-left: 4px;">✕</button>
                                </form>
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Message input form -->
    <form action="{{ route('chat.group.send') }}" method="POST" style="margin-top: 15px; display: flex; gap: 10px;">
        @csrf
        <input type="text" name="message" style="flex-grow: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px;" placeholder="Post message to group..." required autocomplete="off" autofocus>
        <button type="submit" style="background-color: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; cursor: pointer;">
            Post
        </button>
    </form>

</div>
@endsection

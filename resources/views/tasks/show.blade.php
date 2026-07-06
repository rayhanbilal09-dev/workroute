@extends('layouts.app')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('tasks.index') }}" style="text-decoration: none; color: #007bff;">&larr; Back to Task List</a>
</div>

<div style="display: flex; gap: 20px; flex-wrap: wrap;">
    <!-- Main Issue Details -->
    <div style="flex: 2; min-width: 350px; background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
            <div>
                <span style="font-size: 14px; color: #888; font-weight: bold; text-transform: uppercase;">{{ $issue->type }}</span>
                <h1 style="margin: 5px 0 0 0; font-size: 28px;">{{ $issue->id }}: {{ $issue->subject }}</h1>
            </div>
            
            <div style="display: flex; gap: 10px;">
                @can('update', $issue)
                    <a href="{{ route('tasks.edit', $issue->id) }}" style="background-color: #007bff; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 14px;">
                        Edit Issue
                    </a>
                @endcan

                @can('delete', $issue)
                    <form action="{{ route('tasks.destroy', $issue->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this issue?');" style="margin: 0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background-color: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 4px; font-weight: bold; font-size: 14px; cursor: pointer;">
                            Delete
                        </button>
                    </form>
                @endcan
            </div>
        </div>

        <div style="margin-bottom: 25px;">
            <h3 style="margin-bottom: 8px; border-bottom: 1px solid #f0f0f0; padding-bottom: 5px; color: #555;">Description</h3>
            <p style="white-space: pre-line; line-height: 1.6; color: #333; margin: 0;">{{ $issue->description }}</p>
        </div>

        <!-- Attachments Section -->
        <div style="margin-bottom: 25px;">
            <h3 style="margin-bottom: 10px; border-bottom: 1px solid #f0f0f0; padding-bottom: 5px; color: #555;">Attachments</h3>
            @if($issue->attachments->isEmpty())
                <p style="color: #777; font-style: italic;">No attachments uploaded for this issue.</p>
            @else
                <ul style="list-style: none; padding-left: 0; display: flex; flex-direction: column; gap: 12px; margin: 0;">
                    @foreach($issue->attachments as $attachment)
                        <li style="border: 1px solid #e8e8e8; padding: 10px; border-radius: 4px; display: flex; align-items: center; justify-content: space-between; background: #fdfdfd;">
                            <div>
                                <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank" style="font-weight: bold; text-decoration: none; color: #007bff;">
                                    {{ $attachment->file_name }}
                                </a>
                                <small style="display: block; color: #888;">Uploaded at {{ $attachment->created_at->format('d M Y, H:i') }}</small>
                            </div>
                            
                            <!-- Image preview if image file -->
                            @if(in_array(strtolower(pathinfo($attachment->file_name, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $attachment->file_path) }}" style="max-height: 50px; border-radius: 4px; border: 1px solid #ccc;" alt="Preview">
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Sidebar Meta & History logs -->
    <div style="flex: 1; min-width: 300px; display: flex; flex-direction: column; gap: 20px;">
        <!-- Meta Details Box -->
        <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px;">
            <h3 style="margin-top: 0; margin-bottom: 15px; border-bottom: 1px solid #f0f0f0; padding-bottom: 5px; color: #555;">Details</h3>
            
            <div style="display: flex; flex-direction: column; gap: 12px; font-size: 14px;">
                <div>
                    <strong style="color: #666; display: block; margin-bottom: 2px;">Status</strong>
                    <span class="badge status-{{ strtolower(str_replace(' ', '', $issue->status)) }}">
                        {{ $issue->status }}
                    </span>
                </div>
                
                <div>
                    <strong style="color: #666; display: block; margin-bottom: 2px;">Priority</strong>
                    @if($issue->priority == 'Low')
                        <span class="priority-low">↓ Low</span>
                    @elseif($issue->priority == 'Medium')
                        <span class="priority-medium">→ Medium</span>
                    @else
                        <span class="priority-high">↑ High</span>
                    @endif
                </div>

                <div>
                    <strong style="color: #666; display: block; margin-bottom: 2px;">Created By</strong>
                    <span>{{ $issue->creator->name }} (Client)</span>
                </div>

                <div>
                    <strong style="color: #666; display: block; margin-bottom: 2px;">Assigned Worker</strong>
                    @if($issue->assignedTo)
                        <span>{{ $issue->assignedTo->name }}</span>
                    @else
                        <span style="color: #999; font-style: italic;">None</span>
                    @endif
                </div>

                <div>
                    <strong style="color: #666; display: block; margin-bottom: 2px;">Reported Date</strong>
                    <span>{{ $issue->created_at->format('d M Y, H:i') }} ({{ $issue->created_at->diffForHumans() }})</span>
                </div>
            </div>
        </div>

        <!-- Issue Activity Log / History -->
        <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px;">
            <h3 style="margin-top: 0; margin-bottom: 15px; border-bottom: 1px solid #f0f0f0; padding-bottom: 5px; color: #555;">Activity Log</h3>
            
            @if($issue->histories->isEmpty())
                <p style="color: #777; font-style: italic; font-size: 13px;">No history recorded.</p>
            @else
                <ul style="list-style: none; padding-left: 0; margin: 0; display: flex; flex-direction: column; gap: 12px; font-size: 13px;">
                    @foreach($issue->histories as $history)
                        <li style="border-bottom: 1px solid #f9f9f9; padding-bottom: 6px;">
                            <strong>{{ $history->user->name }}</strong>: {{ $history->description }}
                            <div style="color: #999; font-size: 11px; margin-top: 2px;">
                                {{ $history->created_at->format('d M Y, H:i') }} ({{ $history->created_at->diffForHumans() }})
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection

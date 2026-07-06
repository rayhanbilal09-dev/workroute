@extends('layouts.app')

@section('content')
<div style="margin-bottom: 20px;">
    <h1>History Pekerjaan</h1>
    <p>Audit log of all activities, status transitions, and issue assignments.</p>
</div>

<div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px;">
    @if($histories->isEmpty())
        <p style="color: #777; text-align: center;">No activity logs found.</p>
    @else
        <table style="width: 100%; border-collapse: collapse; margin-top: 0;">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Issue ID</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach($histories as $history)
                    <tr>
                        <td>{{ $history->created_at->format('d M Y, H:i:s') }} ({{ $history->created_at->diffForHumans() }})</td>
                        <td>
                            @if($history->issue)
                                <a href="{{ route('tasks.show', $history->issue_id) }}" style="text-decoration: none; color: #007bff; font-weight: bold;">
                                    {{ $history->issue_id }}
                                </a>
                            @else
                                <span style="text-decoration: line-through; color: #999;">{{ $history->issue_id }}</span>
                            @endif
                        </td>
                        <td>{{ $history->user->name }} ({{ ucfirst($history->user->role) }})</td>
                        <td>
                            <span class="badge" style="background-color: #f0f0f0; color: #333; border: 1px solid #ccc; text-transform: uppercase; font-size: 10px;">
                                {{ $history->action }}
                            </span>
                        </td>
                        <td>{{ $history->description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div style="margin-top: 20px;">
            {{ $histories->links() }}
        </div>
    @endif
</div>
@endsection

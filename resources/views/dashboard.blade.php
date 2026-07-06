@extends('layouts.app')

@section('content')
<div style="margin-bottom: 25px;">
    <h1>Dashboard</h1>
    <p>Welcome back, <strong>{{ auth()->user()->name }}</strong>! You are logged in as <strong>{{ ucfirst(auth()->user()->role) }}</strong>.</p>
</div>

<!-- Stats Grid -->
<div style="display: flex; gap: 15px; margin-bottom: 30px; flex-wrap: wrap;">
    <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 15px 25px; min-width: 150px; flex: 1;">
        <h4 style="margin: 0 0 5px 0; color: #666; font-size: 14px;">Total Issues</h4>
        <span style="font-size: 28px; font-weight: bold;">{{ $stats['total'] }}</span>
    </div>
    @if(auth()->user()->role !== 'worker')
        <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 15px 25px; min-width: 150px; flex: 1;">
            <h4 style="margin: 0 0 5px 0; color: #666; font-size: 14px;">Unassigned</h4>
            <span style="font-size: 28px; font-weight: bold; color: #555;">{{ $stats['unassigned'] }}</span>
        </div>
    @endif
    <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 15px 25px; min-width: 150px; flex: 1;">
        <h4 style="margin: 0 0 5px 0; color: #666; font-size: 14px;">Assigned</h4>
        <span style="font-size: 28px; font-weight: bold; color: #d9534f;">{{ $stats['assigned'] }}</span>
    </div>
    <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 15px 25px; min-width: 150px; flex: 1;">
        <h4 style="margin: 0 0 5px 0; color: #666; font-size: 14px;">In Progress</h4>
        <span style="font-size: 28px; font-weight: bold; color: #856404;">{{ $stats['in_progress'] }}</span>
    </div>
    <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 15px 25px; min-width: 150px; flex: 1;">
        <h4 style="margin: 0 0 5px 0; color: #666; font-size: 14px;">Complete</h4>
        <span style="font-size: 28px; font-weight: bold; color: #155724;">{{ $stats['complete'] }}</span>
    </div>
</div>

@if(auth()->user()->role === 'admin')
    <!-- Admin Statistics Visual Placeholder -->
    <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; margin-bottom: 30px;">
        <h3 style="margin-top: 0; margin-bottom: 15px;">Live Statistics (Diagram Placeholder)</h3>
        <div style="background: #fcfcfc; border: 1px dashed #ccc; border-radius: 4px; padding: 30px; text-align: center;">
            <p style="margin: 0; color: #777;">[ Diagram Visualisasi Distribusi Status & Prioritas Akan Ditampilkan Di Sini ]</p>
            <div style="display: flex; justify-content: center; gap: 10px; margin-top: 15px;">
                <div style="height: 100px; width: 30px; background-color: #e0e0e0; border-radius: 3px;" title="Unassigned ({{ $stats['unassigned'] }})"></div>
                <div style="height: {{ $stats['total'] > 0 ? ($stats['assigned'] / $stats['total']) * 100 + 10 : 15 }}px; width: 30px; background-color: #ffe8d6; border-radius: 3px;" title="Assigned ({{ $stats['assigned'] }})"></div>
                <div style="height: {{ $stats['total'] > 0 ? ($stats['in_progress'] / $stats['total']) * 100 + 10 : 15 }}px; width: 30px; background-color: #fff3cd; border-radius: 3px;" title="In Progress ({{ $stats['in_progress'] }})"></div>
                <div style="height: {{ $stats['total'] > 0 ? ($stats['complete'] / $stats['total']) * 100 + 10 : 15 }}px; width: 30px; background-color: #d4edda; border-radius: 3px;" title="Complete ({{ $stats['complete'] }})"></div>
            </div>
            <div style="display: flex; justify-content: center; gap: 18px; margin-top: 8px; font-size: 11px; color: #666;">
                <span>Unassigned</span>
                <span>Assigned</span>
                <span>In Progress</span>
                <span>Complete</span>
            </div>
        </div>
    </div>
@endif

<div style="display: flex; gap: 20px; flex-wrap: wrap;">
    <!-- Recent Issues -->
    <div style="flex: 2; min-width: 300px; background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3 style="margin: 0;">Recent Issues</h3>
            <a href="{{ route('tasks.index') }}" style="text-decoration: none; color: #007bff; font-weight: 500;">View All Tasks</a>
        </div>
        
        @if($issues->isEmpty())
            <p style="color: #777;">No issues found.</p>
        @else
            <table style="margin-top: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Subject</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Priority</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($issues->take(5) as $issue)
                        <tr>
                            <td><a href="{{ route('tasks.show', $issue->id) }}" style="text-decoration: none; color: #007bff; font-weight: bold;">{{ $issue->id }}</a></td>
                            <td>{{ $issue->subject }}</td>
                            <td>{{ $issue->type }}</td>
                            <td>
                                <span class="badge status-{{ strtolower(str_replace(' ', '', $issue->status)) }}">
                                    {{ $issue->status }}
                                </span>
                            </td>
                            <td>
                                @if($issue->priority == 'Low')
                                    <span class="priority-low">↓ Low</span>
                                @elseif($issue->priority == 'Medium')
                                    <span class="priority-medium">→ Medium</span>
                                @else
                                    <span class="priority-high">↑ High</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- History / Activity Feed -->
    <div style="flex: 1; min-width: 300px; background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3 style="margin: 0;">History Pekerjaan</h3>
            <a href="{{ route('tasks.history') }}" style="text-decoration: none; color: #007bff; font-weight: 500;">View Full Log</a>
        </div>

        @if($histories->isEmpty())
            <p style="color: #777;">No recent activity.</p>
        @else
            <ul style="list-style: none; padding-left: 0; margin: 0; display: flex; flex-direction: column; gap: 12px;">
                @foreach($histories->take(5) as $history)
                    <li style="border-bottom: 1px solid #f0f0f0; padding-bottom: 8px; font-size: 13px;">
                        <span style="color: #007bff; font-weight: bold;">{{ $history->issue_id }}</span>: 
                        {{ $history->description }}
                        <div style="color: #999; font-size: 11px; margin-top: 4px;">
                            By {{ $history->user->name }} • {{ $history->created_at->diffForHumans() }}
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1>Task List</h1>
    <a href="{{ route('tasks.create') }}" style="background-color: #007bff; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: bold;">
        + Create New Issue
    </a>
</div>

<div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px;">
    @if($issues->isEmpty())
        <p style="color: #777; text-align: center; padding: 20px;">No tasks found.</p>
    @else
        <table style="width: 100%; border-collapse: collapse; margin-top: 0;">
            <thead>
                <tr>
                    <th>Issue Type</th>
                    <th>Issue ID</th>
                    <th>Subject</th>
                    <th>Assigned To</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th style="text-align: right;">Action Menu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($issues as $issue)
                    <tr>
                        <!-- 1. Issue Type -->
                        <td>{{ $issue->type }}</td>
                        
                        <!-- 2. Issue ID -->
                        <td>
                            <a href="{{ route('tasks.show', $issue->id) }}" style="text-decoration: none; color: #007bff; font-weight: bold;">
                                {{ $issue->id }}
                            </a>
                        </td>
                        
                        <!-- 3. Subject -->
                        <td>{{ $issue->subject }}</td>
                        
                        <!-- 4. Assigned To -->
                        <td>
                            @if($issue->assignedTo)
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    @if($issue->assignedTo->avatar)
                                        <img src="{{ asset('storage/' . $issue->assignedTo->avatar) }}" style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover;" alt="Avatar">
                                    @else
                                        <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($issue->assignedTo->email))) }}?d=mp" style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover;" alt="Avatar">
                                    @endif
                                    <span>{{ $issue->assignedTo->name }}</span>
                                </div>
                            @else
                                <span style="color: #999; font-style: italic;">Unassigned</span>
                            @endif
                        </td>
                        
                        <!-- 5. Status -->
                        <td>
                            <span class="badge status-{{ strtolower(str_replace(' ', '', $issue->status)) }}">
                                {{ $issue->status }}
                            </span>
                        </td>
                        
                        <!-- 6. Priority -->
                        <td>
                            @if($issue->priority == 'Low')
                                <span class="priority-low" title="Low Priority">↓ Low</span>
                            @elseif($issue->priority == 'Medium')
                                <span class="priority-medium" title="Medium Priority">→ Medium</span>
                            @else
                                <span class="priority-high" title="High Priority">↑ High</span>
                            @endif
                        </td>
                        
                        <!-- 7. Action Menu -->
                        <td style="text-align: right; position: relative;">
                            <details style="display: inline-block;">
                                <summary style="cursor: pointer; list-style: none; font-size: 18px; font-weight: bold; padding: 2px 8px; user-select: none;">•••</summary>
                                <div style="position: absolute; right: 0; background: white; border: 1px solid #ccc; box-shadow: 0 2px 5px rgba(0,0,0,0.15); border-radius: 4px; padding: 5px; z-index: 10; min-width: 100px; text-align: left;">
                                    <a href="{{ route('tasks.show', $issue->id) }}" style="display: block; padding: 5px 10px; color: #333; text-decoration: none; font-size: 13px;">Detail</a>
                                    
                                    @can('update', $issue)
                                        <a href="{{ route('tasks.edit', $issue->id) }}" style="display: block; padding: 5px 10px; color: #333; text-decoration: none; font-size: 13px;">Edit</a>
                                    @endcan

                                    @can('delete', $issue)
                                        <form action="{{ route('tasks.destroy', $issue->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this issue?');" style="margin: 0; display: block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="display: block; width: 100%; text-align: left; background: none; border: none; padding: 5px 10px; color: red; font-size: 13px; cursor: pointer;">Hapus</button>
                                        </form>
                                    @endcan
                                </div>
                            </details>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

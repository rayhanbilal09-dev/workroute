@extends('layouts.app')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('tasks.show', $issue->id) }}" style="text-decoration: none; color: #007bff;">&larr; Back to Issue Detail</a>
    <h1 style="margin-top: 10px;">Edit Issue: {{ $issue->id }}</h1>
</div>

<div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; max-width: 700px;">
    <form action="{{ route('tasks.update', $issue->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if(auth()->user()->isAdmin())
            <!-- Admin Field: Edit Everything -->
            <div style="margin-bottom: 15px;">
                <label for="type" style="display: block; font-weight: bold; margin-bottom: 5px;">Issue Type</label>
                <select id="type" name="type" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
                    <option value="Bug" {{ old('type', $issue->type) == 'Bug' ? 'selected' : '' }}>Bug</option>
                    <option value="Improve" {{ old('type', $issue->type) == 'Improve' ? 'selected' : '' }}>Improve</option>
                    <option value="Request" {{ old('type', $issue->type) == 'Request' ? 'selected' : '' }}>Request</option>
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="subject" style="display: block; font-weight: bold; margin-bottom: 5px;">Subject</label>
                <input type="text" id="subject" name="subject" value="{{ old('subject', $issue->subject) }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="description" style="display: block; font-weight: bold; margin-bottom: 5px;">Description</label>
                <textarea id="description" name="description" rows="6" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>{{ old('description', $issue->description) }}</textarea>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="priority" style="display: block; font-weight: bold; margin-bottom: 5px;">Priority</label>
                <select id="priority" name="priority" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
                    <option value="Low" {{ old('priority', $issue->priority) == 'Low' ? 'selected' : '' }}>Low</option>
                    <option value="Medium" {{ old('priority', $issue->priority) == 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="High" {{ old('priority', $issue->priority) == 'High' ? 'selected' : '' }}>High</option>
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="assigned_to" style="display: block; font-weight: bold; margin-bottom: 5px;">Assign To (Worker)</label>
                <select id="assigned_to" name="assigned_to" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="">-- Select Worker (Optional) --</option>
                    @foreach($workers as $worker)
                        <option value="{{ $worker->id }}" {{ old('assigned_to', $issue->assigned_to) == $worker->id ? 'selected' : '' }}>
                            {{ $worker->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="status" style="display: block; font-weight: bold; margin-bottom: 5px;">Status</label>
                <select id="status" name="status" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
                    <option value="Unassigned" {{ old('status', $issue->status) == 'Unassigned' ? 'selected' : '' }}>Unassigned</option>
                    <option value="Assigned" {{ old('status', $issue->status) == 'Assigned' ? 'selected' : '' }}>Assigned</option>
                    <option value="In Progress" {{ old('status', $issue->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Complete" {{ old('status', $issue->status) == 'Complete' ? 'selected' : '' }}>Complete</option>
                </select>
            </div>

        @elseif(auth()->user()->isWorker())
            <!-- Worker Field: ONLY edit Status -->
            <div style="margin-bottom: 15px; background-color: #f0f4f8; padding: 15px; border-radius: 4px; border-left: 4px solid #007bff; font-size: 14px;">
                <p style="margin: 0 0 8px 0;"><strong>Issue Details (Read Only):</strong></p>
                <p style="margin: 0;"><strong>Type:</strong> {{ $issue->type }}</p>
                <p style="margin: 4px 0;"><strong>Subject:</strong> {{ $issue->subject }}</p>
                <p style="margin: 0;"><strong>Description:</strong> {{ $issue->description }}</p>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="status" style="display: block; font-weight: bold; margin-bottom: 5px;">Status</label>
                <select id="status" name="status" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
                    <option value="Assigned" {{ old('status', $issue->status) == 'Assigned' ? 'selected' : '' }}>Assigned</option>
                    <option value="In Progress" {{ old('status', $issue->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Complete" {{ old('status', $issue->status) == 'Complete' ? 'selected' : '' }}>Complete</option>
                </select>
            </div>

        @elseif(auth()->user()->isClient())
            <!-- Client Field: Edit details (ONLY if status is Unassigned) -->
            @if($issue->status === 'Unassigned')
                <div style="margin-bottom: 15px;">
                    <label for="type" style="display: block; font-weight: bold; margin-bottom: 5px;">Issue Type</label>
                    <select id="type" name="type" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
                        <option value="Bug" {{ old('type', $issue->type) == 'Bug' ? 'selected' : '' }}>Bug</option>
                        <option value="Improve" {{ old('type', $issue->type) == 'Improve' ? 'selected' : '' }}>Improve</option>
                        <option value="Request" {{ old('type', $issue->type) == 'Request' ? 'selected' : '' }}>Request</option>
                    </select>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="subject" style="display: block; font-weight: bold; margin-bottom: 5px;">Subject</label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject', $issue->subject) }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="description" style="display: block; font-weight: bold; margin-bottom: 5px;">Description</label>
                    <textarea id="description" name="description" rows="6" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>{{ old('description', $issue->description) }}</textarea>
                </div>
            @else
                <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; border: 1px solid #f5c6cb; margin-bottom: 15px;">
                    You can no longer edit this issue because it has been assigned or processed (Status: <strong>{{ $issue->status }}</strong>).
                </div>
            @endif
        @endif

        @if(!auth()->user()->isWorker() && ($issue->status === 'Unassigned' || auth()->user()->isAdmin()))
            <div style="margin-bottom: 25px;">
                <label for="attachments" style="display: block; font-weight: bold; margin-bottom: 5px;">Add More Attachments (Multiple Files)</label>
                <input type="file" id="attachments" name="attachments[]" accept="image/*,application/zip,application/x-rar-compressed,application/x-zip-compressed,.zip,.rar" style="width: 100%;" multiple>
            </div>
        @endif

        <button type="submit" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; font-size: 16px; border-radius: 4px; cursor: pointer; font-weight: bold;">
            Update Issue
        </button>
    </form>
</div>
@endsection

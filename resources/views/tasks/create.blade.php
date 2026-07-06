@extends('layouts.app')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('tasks.index') }}" style="text-decoration: none; color: #007bff;">&larr; Back to Task List</a>
    <h1 style="margin-top: 10px;">Create New Issue</h1>
</div>

<div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; max-width: 700px;">
    <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div style="margin-bottom: 15px;">
            <label for="type" style="display: block; font-weight: bold; margin-bottom: 5px;">Issue Type</label>
            <select id="type" name="type" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
                <option value="Bug" {{ old('type') == 'Bug' ? 'selected' : '' }}>Bug</option>
                <option value="Improve" {{ old('type') == 'Improve' ? 'selected' : '' }}>Improve</option>
                <option value="Request" {{ old('type') == 'Request' ? 'selected' : '' }}>Request</option>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="subject" style="display: block; font-weight: bold; margin-bottom: 5px;">Subject</label>
            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" placeholder="Issue title/summary" required>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="description" style="display: block; font-weight: bold; margin-bottom: 5px;">Description</label>
            <textarea id="description" name="description" rows="6" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" placeholder="Describe the issue in detail..." required>{{ old('description') }}</textarea>
        </div>

        @if(auth()->user()->isAdmin())
            <div style="margin-bottom: 15px;">
                <label for="priority" style="display: block; font-weight: bold; margin-bottom: 5px;">Priority</label>
                <select id="priority" name="priority" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                    <option value="Medium" {{ old('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }}>High</option>
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="assigned_to" style="display: block; font-weight: bold; margin-bottom: 5px;">Assign To (Worker)</label>
                <select id="assigned_to" name="assigned_to" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="">-- Select Worker (Optional) --</option>
                    @foreach($workers as $worker)
                        <option value="{{ $worker->id }}" {{ old('assigned_to') == $worker->id ? 'selected' : '' }}>
                            {{ $worker->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @else
            <div style="margin-bottom: 15px; background-color: #f9f9f9; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 13px;">
                <strong>Note:</strong> Priority will default to <em>Low</em>. Only administrators can set the priority or assign issues.
            </div>
        @endif

        <div style="margin-bottom: 25px;">
            <label for="attachments" style="display: block; font-weight: bold; margin-bottom: 5px;">Attachments (Multiple Files)</label>
            <input type="file" id="attachments" name="attachments[]" accept="image/*,application/zip,application/x-rar-compressed,application/x-zip-compressed,.zip,.rar" style="width: 100%;" multiple>
            <small style="color: #666; display: block; margin-top: 5px;">Upload photos, repository links (via subject/description), or zip/rar files.</small>
        </div>

        <button type="submit" style="background-color: #28a745; color: white; border: none; padding: 10px 20px; font-size: 16px; border-radius: 4px; cursor: pointer; font-weight: bold;">
            Submit Issue
        </button>
    </form>
</div>
@endsection

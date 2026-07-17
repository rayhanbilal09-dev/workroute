<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\IssueAttachment;
use App\Models\IssueHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class IssueController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Issue::with(['assignedTo', 'creator']);

        if ($user->isAdmin()) {
            // no additional restriction
        } elseif ($user->isWorker()) {
            $query->where('assigned_to', $user->id);
        } else {
            $query->where('creator_id', $user->id);
        }

        if ($request->filled('subject')) {
            $query->where('subject', $request->subject);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('deadline')) {
            $query->whereDate('deadline', $request->deadline);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $issues = $query
            ->orderByRaw("CASE WHEN priority = 'High' THEN 1 WHEN priority = 'Medium' THEN 2 WHEN priority = 'Low' THEN 3 ELSE 4 END")
            ->orderBy('deadline', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        $workers = User::where('role', 'worker')->orderBy('name')->get();

        return view('tasks.index', compact('issues', 'workers'));
    }

    public function create()
    {
        if (!Gate::allows('create', Issue::class)) {
            abort(403, 'Workers cannot create issues.');
        }

        $workers = User::where('role', 'worker')->get();
        return view('tasks.create', compact('workers'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!Gate::allows('create', Issue::class)) {
            abort(403, 'Workers cannot create issues.');
        }

        $rules = [
            'type' => ['required', 'in:Bug,Improve,Request'],
            'subject' => ['required', 'in:LPM UKP,Social Lens,SellerPro'],
            'title' => ['required', 'string', 'max:255'],
            'deadline' => ['nullable', 'date'],
            'description' => ['required', 'string'],
            'attachments.*' => ['nullable', 'file', 'max:10240'], // 10MB limit per file
        ];

        // Only Admin can set priority & assignment on creation
        if ($user->isAdmin()) {
            $rules['priority'] = ['required', 'in:Low,Medium,High'];
            $rules['assigned_to'] = ['nullable', 'exists:users,id'];
        }

        $request->validate($rules);

        // Map fields
        $data = [
            'type' => $request->type,
            'subject' => $request->subject,
            'title' => $request->title,
            'deadline' => $request->deadline,
            'description' => $request->description,
            'creator_id' => $user->id,
            'status' => 'Unassigned',
        ];

        if ($user->isAdmin()) {
            $data['priority'] = $request->priority;
            $data['assigned_to'] = $request->assigned_to;
            if ($request->assigned_to) {
                $data['status'] = 'Assigned';
            }
        } else {
            $data['priority'] = 'Low';
            $data['assigned_to'] = null;
        }

        $issue = Issue::create($data);

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                IssueAttachment::create([
                    'issue_id' => $issue->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        // Log History
        IssueHistory::create([
            'issue_id' => $issue->id,
            'user_id' => $user->id,
            'action' => 'created',
            'description' => 'Issue created by ' . $user->name,
        ]);

        if ($user->isAdmin() && $request->assigned_to) {
            $worker = User::find($request->assigned_to);
            IssueHistory::create([
                'issue_id' => $issue->id,
                'user_id' => $user->id,
                'action' => 'assigned',
                'description' => 'Issue assigned to ' . $worker->name . ' by Admin',
            ]);
        }

        return redirect()->route('tasks.index')->with('success', 'Issue created successfully.');
    }

    public function show(Issue $issue)
    {
        $user = Auth::user();
        if ($user->isWorker() && $issue->assigned_to !== $user->id) {
            abort(403, 'Unauthorized.');
        }
        if ($user->isClient() && $issue->creator_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $issue->load(['assignedTo', 'creator', 'attachments', 'histories.user']);
        return view('tasks.show', compact('issue'));
    }

    public function edit(Issue $issue)
    {
        $user = Auth::user();
        
        // Use Gate/Policy check
        if (!Gate::allows('update', $issue)) {
            abort(403, 'Unauthorized action.');
        }

        $workers = User::where('role', 'worker')->get();
        return view('tasks.edit', compact('issue', 'workers'));
    }

    public function update(Request $request, Issue $issue)
    {
        $user = Auth::user();

        if (!Gate::allows('update', $issue)) {
            abort(403, 'Unauthorized action.');
        }

        $oldStatus = $issue->status;
        $oldPriority = $issue->priority;
        $oldAssigned = $issue->assigned_to;

        if ($user->isAdmin()) {
            $request->validate([
                'type' => ['required', 'in:Bug,Improve,Request'],
                'status' => ['required', 'in:Unassigned,Assigned,In Progress,Complete'],
                'priority' => ['required', 'in:Low,Medium,High'],
                'subject' => ['required', 'in:LPM UKP,Social Lens,SellerPro'],
                'title' => ['required', 'string', 'max:255'],
                'deadline' => ['nullable', 'date'],
                'description' => ['required', 'string'],
                'assigned_to' => ['nullable', 'exists:users,id'],
                'attachments.*' => ['nullable', 'file', 'max:10240'],
            ]);

            $issue->update([
                'type' => $request->type,
                'status' => $request->status,
                'priority' => $request->priority,
                'subject' => $request->subject,
                'title' => $request->title,
                'deadline' => $request->deadline,
                'description' => $request->description,
                'assigned_to' => $request->assigned_to,
            ]);

        } elseif ($user->isWorker()) {
            $request->validate([
                'status' => ['required', 'in:Assigned,In Progress,Complete'],
            ]);

            $issue->update([
                'status' => $request->status,
            ]);

        } else { // Client
            // Client can only edit if status is Unassigned
            $request->validate([
                'type' => ['required', 'in:Bug,Improve,Request'],
                'subject' => ['required', 'in:LPM UKP,Social Lens,SellerPro'],
                'title' => ['required', 'string', 'max:255'],
                'deadline' => ['nullable', 'date'],
                'description' => ['required', 'string'],
                'attachments.*' => ['nullable', 'file', 'max:10240'],
            ]);

            $issue->update([
                'type' => $request->type,
                'subject' => $request->subject,
                'title' => $request->title,
                'deadline' => $request->deadline,
                'description' => $request->description,
            ]);
        }

        // Handle attachment uploads in edit
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                IssueAttachment::create([
                    'issue_id' => $issue->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        // Log Changes
        if ($oldStatus !== $issue->status) {
            IssueHistory::create([
                'issue_id' => $issue->id,
                'user_id' => $user->id,
                'action' => 'status_updated',
                'description' => "Status changed from '{$oldStatus}' to '{$issue->status}' by {$user->name}",
            ]);
        }

        if ($oldPriority !== $issue->priority) {
            IssueHistory::create([
                'issue_id' => $issue->id,
                'user_id' => $user->id,
                'action' => 'priority_updated',
                'description' => "Priority changed from '{$oldPriority}' to '{$issue->priority}' by {$user->name}",
            ]);
        }

        if ($oldAssigned !== $issue->assigned_to) {
            $newWorkerName = $issue->assignedTo ? $issue->assignedTo->name : 'None';
            IssueHistory::create([
                'issue_id' => $issue->id,
                'user_id' => $user->id,
                'action' => 'assignment_updated',
                'description' => "Assigned worker changed to '{$newWorkerName}' by {$user->name}",
            ]);
        }

        IssueHistory::create([
            'issue_id' => $issue->id,
            'user_id' => $user->id,
            'action' => 'updated',
            'description' => "Issue details updated by {$user->name}",
        ]);

        return redirect()->route('tasks.show', $issue->id)->with('success', 'Issue updated successfully.');
    }

    public function destroy(Issue $issue)
    {
        if (!Gate::allows('delete', $issue)) {
            abort(403, 'Unauthorized action.');
        }

        $issue->delete();
        return redirect()->route('tasks.index')->with('success', 'Issue deleted successfully.');
    }

    public function history()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $histories = IssueHistory::with(['issue', 'user'])->latest()->paginate(25);
        } elseif ($user->isWorker()) {
            $histories = IssueHistory::with(['issue', 'user'])
                ->whereHas('issue', function ($query) use ($user) {
                    $query->where('assigned_to', $user->id);
                })
                ->latest()
                ->paginate(25);
        } else {
            $histories = IssueHistory::with(['issue', 'user'])
                ->whereHas('issue', function ($query) use ($user) {
                    $query->where('creator_id', $user->id);
                })
                ->latest()
                ->paginate(25);
        }

        return view('tasks.history', compact('histories'));
    }
}

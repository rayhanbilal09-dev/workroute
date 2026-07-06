<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\IssueHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Guest dashboard - public, read-only overview.
     */
    public function guest()
    {
        // If already logged in, redirect to authenticated dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $stats = [
            'total' => Issue::count(),
            'unassigned' => Issue::where('status', 'Unassigned')->count(),
            'assigned' => Issue::where('status', 'Assigned')->count(),
            'in_progress' => Issue::where('status', 'In Progress')->count(),
            'complete' => Issue::where('status', 'Complete')->count(),
        ];

        $recentIssues = Issue::with(['assignedTo', 'creator'])->latest()->limit(5)->get();

        return view('guest-dashboard', compact('stats', 'recentIssues'));
    }

    /**
     * Authenticated dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Stats logic
        $stats = [
            'total' => 0,
            'unassigned' => 0,
            'assigned' => 0,
            'in_progress' => 0,
            'complete' => 0,
        ];

        // 2. Fetch Issues and History based on role
        if ($user->isAdmin()) {
            $issues = Issue::with(['assignedTo', 'creator'])->latest()->get();
            $histories = IssueHistory::with(['issue', 'user'])->latest()->limit(20)->get();

            // Populate stats for Admin
            $stats['total'] = $issues->count();
            $stats['unassigned'] = $issues->where('status', 'Unassigned')->count();
            $stats['assigned'] = $issues->where('status', 'Assigned')->count();
            $stats['in_progress'] = $issues->where('status', 'In Progress')->count();
            $stats['complete'] = $issues->where('status', 'Complete')->count();

        } elseif ($user->isWorker()) {
            $issues = Issue::with(['assignedTo', 'creator'])
                ->where('assigned_to', $user->id)
                ->latest()
                ->get();
            
            $histories = IssueHistory::with(['issue', 'user'])
                ->whereHas('issue', function ($query) use ($user) {
                    $query->where('assigned_to', $user->id);
                })
                ->latest()
                ->limit(20)
                ->get();

            $stats['total'] = $issues->count();
            $stats['assigned'] = $issues->where('status', 'Assigned')->count();
            $stats['in_progress'] = $issues->where('status', 'In Progress')->count();
            $stats['complete'] = $issues->where('status', 'Complete')->count();

        } else { // client
            $issues = Issue::with(['assignedTo', 'creator'])
                ->where('creator_id', $user->id)
                ->latest()
                ->get();

            $histories = IssueHistory::with(['issue', 'user'])
                ->whereHas('issue', function ($query) use ($user) {
                    $query->where('creator_id', $user->id);
                })
                ->latest()
                ->limit(20)
                ->get();

            $stats['total'] = $issues->count();
            $stats['unassigned'] = $issues->where('status', 'Unassigned')->count();
            $stats['assigned'] = $issues->where('status', 'Assigned')->count();
            $stats['in_progress'] = $issues->where('status', 'In Progress')->count();
            $stats['complete'] = $issues->where('status', 'Complete')->count();
        }

        return view('dashboard', compact('issues', 'histories', 'stats'));
    }
}

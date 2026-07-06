<?php

namespace App\Policies;

use App\Models\Issue;
use App\Models\User;

class IssuePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Filtered in query level
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Issue $issue): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isWorker()) {
            return $issue->assigned_to == $user->id;
        }

        if ($user->isClient()) {
            return $issue->creator_id == $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only Admin and Client can create issues
        return $user->isAdmin() || $user->isClient();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Issue $issue): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isWorker()) {
            // Worker cannot edit the issue details, but they can update status. 
            // We'll enforce this field restriction in the controller/request.
            return $issue->assigned_to == $user->id;
        }

        if ($user->isClient()) {
            // Client can only edit if it is their issue AND status is Unassigned.
            return $issue->creator_id == $user->id && $issue->status === 'Unassigned';
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Issue $issue): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isClient()) {
            // Client can only delete if it is their issue AND status is Unassigned.
            return $issue->creator_id == $user->id && $issue->status === 'Unassigned';
        }

        // Worker cannot delete.
        return false;
    }
}

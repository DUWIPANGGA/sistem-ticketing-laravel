<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        return $user->id === $ticket->user_id || $user->role === 'technician';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     * User can edit if: (1) admin/technician, OR (2) ticket owner AND unassigned AND still open
     */
    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->role === 'technician') return true;

        // Regular user: can edit only if unassigned AND status is still 'open'
        return $user->id === $ticket->user_id
            && is_null($ticket->assigned_to)
            && $ticket->status === 'open';
    }

    /**
     * Determine whether the user can delete the model.
     * User can delete if: (1) admin, OR (2) ticket owner AND unassigned AND still open
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        // Regular user: can delete only if unassigned AND status is still 'open'
        return $user->id === $ticket->user_id
            && is_null($ticket->assigned_to)
            && $ticket->status === 'open';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Ticket $ticket): bool
    {
        return $user->id === $ticket->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return false;
    }
}

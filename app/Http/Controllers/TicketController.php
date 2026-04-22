<?php

namespace App\Http\Controllers;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\TicketUpdate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Ticket::with('creator', 'assignee')->orderBy('created_at', 'desc');

        // Role-based visibility
        if (! in_array(Auth::user()->role, ['admin', 'technician'])) {
            $query->where('user_id', Auth::id());
        }

        // Filtering & Searching
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'ilike', "%{$search}%")
                    ->orWhere('ticket_number', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        if ($request->filled('assignee_id')) {
            $query->where('assigned_to', $request->input('assignee_id'));
        }

        // Shortcut for "assigned to me"
        if ($request->has('my_tickets') && in_array(Auth::user()->role, ['admin', 'technician'])) {
            $query->where('assigned_to', Auth::id());
        }

        $tickets = $query->paginate(15)->withQueryString();

        $statuses = TicketStatus::cases();
        $priorities = TicketPriority::cases();

        return view('tickets.index', compact('tickets', 'statuses', 'priorities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $priorities = TicketPriority::cases();
        $statuses = TicketStatus::cases(); // Usually only 'open' is available for creation
        $categories = \App\Models\Category::all();

        return view('tickets.create', compact('priorities', 'statuses', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => ['required', new Enum(TicketPriority::class)],
            'category' => 'required|string|max:100',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:2048', // Max 2MB
        ]);

        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachmentPaths[] = $file->store('attachments', 'public');
            }
        }

        // Calculate SLA based on priority
        $slaDueAt = $this->calculateSla($request->priority);
        $estimatedCompletionAt = $this->calculateEstimatedCompletionTime($request->priority);

        $ticket = Ticket::create([
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority,
            'category' => $request->category,
            'attachments' => $attachmentPaths,
            'estimated_completion_at' => $estimatedCompletionAt,
            'sla_due_at' => $slaDueAt,
            'user_id' => Auth::id(),
            'status' => TicketStatus::OPEN->value, // Default to open
        ]);

        // Optionally create an initial update record
        TicketUpdate::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'comment' => 'Ticket created.',
            'status' => TicketStatus::OPEN->value,
        ]);

        // Notify admins about new ticket
        $admins = \App\Models\User::whereIn('role', ['admin', 'technician'])->get();
        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\TicketCreatedNotification($ticket));

        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Ticket created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        Gate::authorize('view', $ticket);

        $ticket->load('creator', 'assignee');
        // Load only top-level comments with nested replies
        $comments = $ticket->updates()
            ->whereNull('parent_id')
            ->with(['user', 'replies.user', 'replies.replies.user'])
            ->orderBy('created_at')
            ->get();
        $statuses = TicketStatus::cases();
        $priorities = TicketPriority::cases();

        return view('tickets.show', compact('ticket', 'comments', 'statuses', 'priorities'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        Gate::authorize('update', $ticket); // Implement authorization policy

        $priorities = TicketPriority::cases();
        $statuses = TicketStatus::cases();
        $categories = \App\Models\Category::all();

        return view('tickets.edit', compact('ticket', 'priorities', 'statuses', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        Gate::authorize('update', $ticket); // Implement authorization policy

        $request->validate([
            'subject' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'priority' => ['sometimes', 'required', new Enum(TicketPriority::class)],
            'status' => ['sometimes', 'required', new Enum(TicketStatus::class)],
            'category' => 'sometimes|required|string|max:100',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:2048', // Max 2MB
            'estimated_completion_at' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $oldStatus = $ticket->status;
        $oldPriority = $ticket->priority;
        $attachmentPaths = $ticket->attachments ?? [];

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachmentPaths[] = $file->store('attachments', 'public');
            }
        }

        $dataToUpdate = [
            'subject' => $request->input('subject', $ticket->subject),
            'description' => $request->input('description', $ticket->description),
            'category' => $request->input('category', $ticket->category),
            'attachments' => $attachmentPaths,
            'estimated_completion_at' => $request->input('estimated_completion_at', $ticket->estimated_completion_at),
        ];

        if (Auth::user()->role === 'admin' || Auth::user()->role === 'technician') {
            $dataToUpdate['priority'] = $request->input('priority', $ticket->priority);
            $dataToUpdate['status'] = $request->input('status', $ticket->status);
            $dataToUpdate['assigned_to'] = $request->input('assigned_to', $ticket->assigned_to);

            if ($request->has('priority') && $request->priority !== $oldPriority) {
                $dataToUpdate['sla_due_at'] = $this->calculateSla($request->priority, $ticket->created_at);
                $dataToUpdate['estimated_completion_at'] = $this->calculateEstimatedCompletionTime($request->priority, $ticket->created_at);
            }
        }

        $ticket->update($dataToUpdate);

        // Create a ticket update record if any significant field changed
        if ($ticket->isDirty('status') || $ticket->isDirty('priority') || $ticket->isDirty('assigned_to') || $request->has('comment')) {
            TicketUpdate::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'comment' => $request->comment ?? ($ticket->status !== $oldStatus ? 'Status changed from '.$oldStatus.' to '.$ticket->status.'.' : ''),
                'status' => $ticket->status !== $oldStatus ? $ticket->status : null,
                'priority' => $ticket->priority !== $oldPriority ? $ticket->priority : null,
                // Attachment updates are handled separately if needed, or can be added here
            ]);

            // Dispatch Email Notification
            if ($ticket->creator && $ticket->creator->id !== Auth::id()) {
                Mail::to($ticket->creator->email)->queue(new \App\Mail\TicketUpdatedMail($ticket, 'Your ticket was updated by '.Auth::user()->name.'.'));
                $ticket->creator->notify(new \App\Notifications\TicketUpdatedNotification($ticket, 'Your ticket was updated by '.Auth::user()->name.'.'));
            }

            // Notify Assignee if assigned_to changed and is not the current user
            if ($ticket->wasChanged('assigned_to') && $ticket->assignee && $ticket->assigned_to !== Auth::id()) {
                $ticket->assignee->notify(new \App\Notifications\TicketAssignedNotification($ticket));
            }
        }

        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Ticket updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        Gate::authorize('delete', $ticket); // Implement authorization policy

        // Delete associated attachment
        if ($ticket->attachment && Storage::disk('public')->exists($ticket->attachment)) {
            Storage::disk('public')->delete($ticket->attachment);
        }
        if ($ticket->attachments) {
            foreach ($ticket->attachments as $att) {
                if (Storage::disk('public')->exists($att)) {
                    Storage::disk('public')->delete($att);
                }
            }
        }

        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully!');
    }

    /**
     * Handle adding a comment or update to a ticket.
     */
    public function addUpdate(Request $request, Ticket $ticket)
    {
        Gate::authorize('view', $ticket);

        $isAdminOrTech = in_array(Auth::user()->role, ['admin', 'technician']);

        // Admin/tech can submit without a comment if changing status/priority
        $commentRequired = ! $isAdminOrTech || (! $request->filled('status') && ! $request->filled('priority'));

        $request->validate([
            'comment' => $commentRequired ? 'required|string' : 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:2048',
            'status' => ['nullable', new Enum(TicketStatus::class)],
            'priority' => ['nullable', new Enum(TicketPriority::class)],
            'parent_id' => 'nullable|exists:ticket_updates,id',
        ]);

        // Auto-generate comment for admin status/priority change if comment is empty
        $comment = $request->comment;
        if (empty($comment) && $isAdminOrTech) {
            $parts = [];
            if ($request->filled('status')) {
                $parts[] = 'Status diubah menjadi: '.\App\Enums\TicketStatus::from($request->status)->label();
            }
            if ($request->filled('priority')) {
                $parts[] = 'Prioritas diubah menjadi: '.\App\Enums\TicketPriority::from($request->priority)->label();
            }
            $comment = implode(', ', $parts).'.';
        }

        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachmentPaths[] = $file->store('attachments', 'public');
            }
        }

        $updateData = [
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'comment' => $comment,
            'attachments' => empty($attachmentPaths) ? null : $attachmentPaths,
            'parent_id' => $request->parent_id,
        ];

        if (in_array(Auth::user()->role, ['admin', 'technician'])) {
            $updateData['status'] = $request->status ?: null;
            $updateData['priority'] = $request->priority ?: null;
        }

        TicketUpdate::create($updateData);

        if (in_array(Auth::user()->role, ['admin', 'technician'])) {
            if ($request->status && $ticket->status !== $request->status) {
                $ticket->status = $request->status;
                $ticket->save();
            }
            if ($request->priority && $ticket->priority !== $request->priority) {
                $ticket->priority = $request->priority;
                $ticket->save();
            }
        }

        // Dispatch Email Notification
        if ($ticket->creator && $ticket->creator->id !== Auth::id()) {
            Mail::to($ticket->creator->email)->queue(new \App\Mail\TicketUpdatedMail($ticket, 'A new comment was added by '.Auth::user()->name.'.'));
            $ticket->creator->notify(new \App\Notifications\TicketUpdatedNotification($ticket, 'A new comment was added by '.Auth::user()->name.'.'));
        }
        if ($ticket->creator && $ticket->creator->id === Auth::id()) {
            // User replied, notify assignee or admins
            if ($ticket->assignee && $ticket->assignee->id !== Auth::id()) {
                $ticket->assignee->notify(new \App\Notifications\TicketUpdatedNotification($ticket, 'User replied on a ticket assigned to you.'));
            } else {
                $admins = \App\Models\User::whereIn('role', ['admin', 'technician'])->get();
                \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\TicketUpdatedNotification($ticket, 'User replied on their ticket.'));
            }
        }

        return back()->with('success', 'Komentar berhasil dikirim!');
    }

    /**
     * Show ticket tracking/history.
     */
    public function track(Ticket $ticket)
    {
        Gate::authorize('view', $ticket);

        $ticket->load('updates.user', 'creator', 'assignee');

        return view('tickets.track', compact('ticket'));
    }

    /**
     * Handle rating and feedback submission for a completed ticket.
     */
    public function rate(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        if (! in_array($ticket->status, [TicketStatus::RESOLVED->value, TicketStatus::CLOSED->value])) {
            return back()->with('error', 'You can only rate resolved or closed tickets.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string',
        ]);

        $ticket->update([
            'rating' => $request->rating,
            'feedback' => $request->feedback,
        ]);

        return back()->with('success', 'Thank you for your feedback!');
    }

    /**
     * Calculate SLA Due Date
     */
    private function calculateSla($priority, $startTime = null)
    {
        $time = $startTime ? Carbon::parse($startTime) : Carbon::now();

        return match ($priority) {
            TicketPriority::LOW->value => (clone $time)->addHours(48),
            TicketPriority::MEDIUM->value => (clone $time)->addHours(24),
            TicketPriority::HIGH->value => (clone $time)->addHours(4),
            TicketPriority::URGENT->value => (clone $time)->addHours(1),
            default => null,
        };
    }

    /**
     * Calculate Estimated Completion Time based on average fix time of resolved tickets
     */
    private function calculateEstimatedCompletionTime($priority, $startTime = null)
    {
        $time = $startTime ? Carbon::parse($startTime) : Carbon::now();

        $tickets = Ticket::where('priority', $priority)
            ->whereIn('status', [TicketStatus::RESOLVED->value, TicketStatus::CLOSED->value])
            ->orderBy('updated_at', 'desc')
            ->take(50)
            ->get();

        if ($tickets->count() > 0) {
            $totalMinutes = 0;
            foreach ($tickets as $t) {
                // Determine resolution time (updated_at minus created_at)
                $totalMinutes += $t->created_at->diffInMinutes($t->updated_at);
            }
            $avgMinutes = $totalMinutes / $tickets->count();

            return (clone $time)->addMinutes((int) round($avgMinutes));
        }

        // Fallback to SLA limits if not enough historical data
        return $this->calculateSla($priority, $time);
    }
}

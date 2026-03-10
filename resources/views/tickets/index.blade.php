@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Tickets</h1>
        <p class="text-gray-500 mt-1 text-sm">Manage and track all support requests.</p>
    </div>
    <a href="{{ route('tickets.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-xl shadow-lg shadow-blue-500/25 text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-500 transition-all transform hover:-translate-y-0.5">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Create New Ticket
    </a>
</div>

<!-- Search and Filter Form -->
<div class="bg-white border border-gray-200/50 rounded-2xl p-4 shadow-sm mb-6">
    <form action="{{ route('tickets.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        
        <div class="flex-1">
            <label for="search" class="sr-only">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by subject or ticket #" 
                       class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 sm:text-sm">
            </div>
        </div>

        <div class="w-full md:w-48">
            <label for="status" class="sr-only">Status</label>
            <select name="status" id="status" class="block w-full py-2 pl-3 pr-10 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 sm:text-sm appearance-none">
                <option value="">All Statuses</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>{{ $status->label() }}</option>
                @endforeach
            </select>
        </div>

        <div class="w-full md:w-48">
            <label for="priority" class="sr-only">Priority</label>
            <select name="priority" id="priority" class="block w-full py-2 pl-3 pr-10 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 sm:text-sm appearance-none">
                <option value="">All Priorities</option>
                @foreach ($priorities as $priority)
                    <option value="{{ $priority->value }}" {{ request('priority') === $priority->value ? 'selected' : '' }}>{{ $priority->label() }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-500 w-full sm:w-auto transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status', 'priority']))
                <a href="{{ route('tickets.index') }}" class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-gray-500 w-full sm:w-auto transition-colors">
                    Reset
                </a>
            @endif
        </div>
        
    </form>
</div>

@if (session('success'))
    <div class="bg-green-500/10 border border-green-500/20 text-green-600 px-4 py-3 rounded-xl mb-6 flex items-center shadow-lg backdrop-blur-sm" role="alert">
        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="block sm:inline font-medium">{{ session('success') }}</span>
    </div>
@endif

<div class="bg-white border border-gray-200/50 rounded-2xl overflow-hidden shadow-xl">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-700/50">
            <thead class="bg-gray-50/50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ticket #</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Subject</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Priority</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created By</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Assigned To</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created At</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700/50 relative">
                @forelse ($tickets as $ticket)
                    <tr class="hover:bg-gray-100/25 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <a href="{{ route('tickets.show', $ticket->id) }}" class="text-blue-600 hover:text-blue-300 transition-colors">
                                {{ $ticket->ticket_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-700">{{ Str::limit($ticket->subject, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $ticket->category }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border
                                {{ match($ticket->status) {
                                    'open' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                                    'in_progress' => 'bg-blue-500/10 text-blue-600 border-blue-500/20',
                                    'on_hold' => 'bg-orange-500/10 text-orange-400 border-orange-500/20',
                                    'resolved' => 'bg-green-500/10 text-green-600 border-green-500/20',
                                    'closed' => 'bg-red-500/10 text-red-600 border-red-500/20',
                                    default => 'bg-gray-500/10 text-gray-500 border-gray-500/20',
                                } }}">
                                {{ \App\Enums\TicketStatus::from($ticket->status)->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border
                                {{ match($ticket->priority) {
                                    'low' => 'bg-green-500/10 text-green-600 border-green-500/20',
                                    'medium' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                                    'high' => 'bg-orange-500/10 text-orange-400 border-orange-500/20',
                                    'urgent' => 'bg-red-500/10 text-red-500 border-red-500/20',
                                    default => 'bg-gray-500/10 text-gray-500 border-gray-500/20',
                                } }}">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 
                                    {{ match($ticket->priority) {
                                        'low' => 'bg-green-400',
                                        'medium' => 'bg-yellow-400',
                                        'high' => 'bg-orange-400',
                                        'urgent' => 'bg-red-500 animate-pulse',
                                        default => 'bg-gray-400',
                                    } }}"></span>
                                {{ \App\Enums\TicketPriority::from($ticket->priority)->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $ticket->creator->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $ticket->assignee->name ?? 'Unassigned' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $ticket->created_at->format('M j, Y g:i A') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                            <a href="{{ route('tickets.show', $ticket->id) }}" class="text-gray-500 hover:text-gray-900 transition-colors" title="View">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            @can('update', $ticket)
                                <a href="{{ route('tickets.edit', $ticket->id) }}" class="text-indigo-600 hover:text-indigo-300 transition-colors" title="Edit">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                            @endcan
                            @can('delete', $ticket)
                                <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-300 transition-colors" title="Delete">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50/50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">No tickets found</h3>
                                <p class="text-gray-500 text-sm">Get started by creating a new ticket.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $tickets->links() }}
</div>
@endsection
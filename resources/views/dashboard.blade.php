@extends('layouts.app')

@section('content')
<!-- Welcome Banner -->
<div class="relative overflow-hidden rounded-2xl bg-white border border-gray-200/50 p-8 shadow-xl mb-8">
    <div class="absolute inset-0 z-0">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-blue-600/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-indigo-600/10 rounded-full blur-3xl"></div>
    </div>
    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome to your Dashboard</h1>
            <p class="text-gray-500 text-lg">Manage your tickets, track progress, and stay updated seamlessly.</p>
        </div>
        <a href="{{ route('tickets.create') }}" class="inline-flex items-center px-5 py-3 border border-transparent text-base font-medium rounded-xl shadow-lg shadow-blue-500/25 text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-500 transition-all transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Create New Ticket
        </a>
    </div>
</div>

<!-- Quick Stats placeholder -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white border border-gray-200/50 rounded-2xl p-6 shadow-lg flex items-center justify-between group hover:border-blue-500/50 transition-colors">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Total Tickets</p>
            <p class="text-3xl font-bold tracking-tight text-gray-900 group-hover:text-blue-600 transition-colors">{{ $totalTickets }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-gray-50/50 flex items-center justify-center border border-gray-200">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
        </div>
    </div>
    
    <div class="bg-white border border-gray-200/50 rounded-2xl p-6 shadow-lg flex items-center justify-between group hover:border-yellow-500/50 transition-colors">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Open Tickets</p>
            <p class="text-3xl font-bold tracking-tight text-gray-900 group-hover:text-yellow-600 transition-colors">{{ $openTickets }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-gray-50/50 flex items-center justify-center border border-gray-200">
            <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>
    
    <div class="bg-white border border-gray-200/50 rounded-2xl p-6 shadow-lg flex items-center justify-between group hover:border-green-500/50 transition-colors">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Resolved Tickets</p>
            <p class="text-3xl font-bold tracking-tight text-gray-900 group-hover:text-green-600 transition-colors">{{ $resolvedTickets }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-gray-50/50 flex items-center justify-center border border-gray-200">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
    </div>
    
    <div class="bg-white border border-gray-200/50 rounded-2xl p-6 shadow-lg flex items-center justify-between group hover:border-red-500/50 transition-colors">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">SLA Breached</p>
            <p class="text-3xl font-bold tracking-tight text-gray-900 group-hover:text-red-600 transition-colors">{{ $slaBreachedTickets }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-gray-50/50 flex items-center justify-center border border-gray-200">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>
</div>

@if(Auth::user()->role !== 'user')
<!-- Assigned Tickets -->
<div class="bg-white border border-gray-200/50 rounded-2xl shadow-xl overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-200/50 flex items-center justify-between bg-gray-50/50">
        <h2 class="text-lg font-bold text-gray-900">Tickets Assigned to You</h2>
        <a href="{{ route('tickets.index', ['status' => 'open']) }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">View all assignments</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ticket #</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Priority</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($assignedTickets as $ticket)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                        <a href="{{ route('tickets.show', $ticket->id) }}">{{ $ticket->ticket_number }}</a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ Str::limit($ticket->subject, 40) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border
                            {{ match($ticket->priority) {
                                'low' => 'bg-green-100 text-green-800 border-green-200',
                                'medium' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                'high' => 'bg-orange-100 text-orange-800 border-orange-200',
                                'urgent' => 'bg-red-100 text-red-800 border-red-200',
                                default => 'bg-gray-100 text-gray-800 border-gray-200',
                            } }}">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border
                            {{ match($ticket->status) {
                                'open' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'in_progress' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                'resolved' => 'bg-green-100 text-green-800 border-green-200',
                                default => 'bg-gray-100 text-gray-800 border-gray-200',
                            } }}">
                            {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $ticket->created_at->diffForHumans() }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500 italic">
                        No active tickets assigned to you.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
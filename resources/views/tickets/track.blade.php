@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('tickets.show', $ticket->id) }}" class="text-gray-500 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Ticket Tracking</h1>
            </div>
            <p class="text-gray-500 font-mono pl-8">#{{ $ticket->ticket_number }}</p>
        </div>
    </div>

    <!-- Current Status Summary -->
    <div class="bg-white border border-gray-200/50 rounded-2xl shadow-xl overflow-hidden p-6 md:p-8">
        <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">Current Status</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-200/50">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Subject</p>
                <p class="text-sm font-semibold text-gray-900 truncate" title="{{ $ticket->subject }}">{{ $ticket->subject }}</p>
            </div>
            <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-200/50">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Status</p>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium border
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
            </div>
            <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-200/50">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Priority</p>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium border
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
            </div>
        </div>
    </div>

    <!-- Timeline History -->
    <div class="bg-white border border-gray-200/50 rounded-2xl shadow-xl overflow-hidden p-6 md:p-8">
        <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-8 border-b border-gray-200/50 pb-4">Activity Timeline</h2>
        
        <div class="relative ml-2 md:ml-6">
            <div class="absolute border-l-2 border-gray-200 h-full top-2 left-[5px] md:left-[11px]"></div>
            <ul class="list-none p-0 m-0 space-y-8">
                <!-- Original Ticket Creation -->
                <li class="relative pl-8 md:pl-12">
                    <div class="absolute top-1.5 left-[-3px] md:left-1 w-3.5 h-3.5 bg-gray-50 border-2 border-indigo-500 rounded-full z-10 shadow-[0_0_0_4px_rgba(79,70,229,0.1)]"></div>
                    <div class="bg-gray-50/50 rounded-2xl p-5 border border-gray-200/50 shadow-sm relative group">
                        <!-- Arrow pointing to dot -->
                        <div class="absolute top-2.5 -left-2 w-2 h-2 bg-gray-50/50 border-t border-l border-gray-200/50 rotate-[-45deg] z-0 hidden md:block"></div>
                        
                        <div class="flex items-center gap-3 mb-3 pb-3 border-b border-gray-800">
                             <div class="w-8 h-8 rounded-full bg-indigo-500/20 text-indigo-600 flex items-center justify-center font-bold text-xs uppercase">{{ substr($ticket->creator->name ?? '?', 0, 1) }}</div>
                            <div>
                                <p class="font-bold text-gray-800 text-sm">{{ $ticket->creator->name ?? 'Unknown User' }} <span class="text-gray-500 font-normal">created the ticket</span></p>
                                <p class="text-xs text-gray-500 font-mono mt-0.5">{{ $ticket->created_at->format('M j, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </li>

                <!-- Updates -->
                @foreach ($ticket->updates->sortBy('created_at') as $update)
                    <li class="relative pl-8 md:pl-12">
                        <div class="absolute top-1.5 left-[-3px] md:left-1 w-3.5 h-3.5 bg-gray-50 border-2 border-blue-500 rounded-full z-10"></div>
                        <div class="bg-gray-50/30 hover:bg-gray-50/60 rounded-2xl p-5 border border-gray-200/50 shadow-sm transition-colors relative group">
                            <!-- Arrow pointing to dot -->
                            <div class="absolute top-2.5 -left-2 w-2 h-2 bg-gray-50/30 group-hover:bg-gray-50/60 border-t border-l border-gray-200/50 rotate-[-45deg] z-0 transition-colors hidden md:block"></div>
                            
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-8 h-8 rounded-full bg-blue-500/20 text-blue-600 flex items-center justify-center font-bold text-xs uppercase">{{ substr($update->user->name ?? 'S', 0, 1) }}</div>
                                <div>
                                    <p class="font-bold text-gray-800 text-sm">{{ $update->user->name ?? 'System' }}</p>
                                    <p class="text-xs text-gray-500 font-mono mt-0.5">{{ $update->created_at->format('M j, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                            
                            @if ($update->comment)
                                <div class="mt-3 text-gray-700 text-sm bg-white/50 rounded-xl p-3 border border-gray-200/30">
                                    {{ $update->comment }}
                                </div>
                            @endif

                            <div class="mt-3 flex flex-wrap gap-2">
                                @if ($update->status)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-xs font-medium bg-white border border-gray-200 text-gray-500">
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                                        Status: <span class="text-gray-900">{{ \App\Enums\TicketStatus::from($update->status)->label() }}</span>
                                    </span>
                                @endif
                                
                                @if ($update->priority)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-xs font-medium bg-white border border-gray-200 text-gray-500">
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                                        Priority: <span class="text-gray-900">{{ \App\Enums\TicketPriority::from($update->priority)->label() }}</span>
                                    </span>
                                @endif
                            </div>

                            @if ($update->attachment)
                                <div class="mt-4 pt-3 border-t border-gray-800/80">
                                    <a href="{{ Storage::url($update->attachment) }}" target="_blank" class="inline-flex items-center text-xs font-medium text-blue-600 hover:text-blue-300 transition-colors">
                                        <svg class="w-4 h-4 mr-1 pb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        View Attached File
                                    </a>
                                </div>
                            @endif
                        </div>
                    </li>
                @endforeach
                
                @if ($ticket->updates->isEmpty())
                    <li class="pl-8 md:pl-12 pt-4">
                        <p class="text-sm text-gray-500 italic">No updates have been posted to this ticket yet.</p>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
@endsection
@php
    $isSystemMsg =
        str_starts_with($comment->comment ?? '', 'Ticket created') ||
        str_starts_with($comment->comment ?? '', 'Status changed');
    $isOwnComment = Auth::id() === $comment->user_id;
    $bgColor = $depth === 0 ? 'bg-white' : 'bg-gray-50/70';
    $authorName = $comment->user->name ?? 'System';
    $authorRole = $comment->user->role ?? '';
    $avatarColor = match ($authorRole) {
        'admin' => 'bg-red-500/20 text-red-600',
        'technician' => 'bg-purple-500/20 text-purple-600',
        default => 'bg-blue-500/20 text-blue-600',
    };
    $roleBadgeColor = match ($authorRole) {
        'admin' => 'bg-red-100 text-red-700',
        'technician' => 'bg-purple-100 text-purple-700',
        default => 'bg-gray-100 text-gray-600',
    };
    $roleLabel = match ($authorRole) {
        'admin' => 'Admin',
        'technician' => 'Teknisi',
        default => 'User',
    };
@endphp

<div
    class="group {{ $bgColor }} {{ $depth > 0 ? 'border-l-2 border-blue-200/70 ml-8' : '' }} px-6 py-4 hover:bg-blue-50/20 transition-colors">
    <div class="flex items-start gap-3">
        {{-- Avatar --}}
        <div
            class="w-8 h-8 rounded-full {{ $avatarColor }} flex items-center justify-center font-bold text-sm uppercase flex-shrink-0 mt-0.5">
            {{ substr($authorName, 0, 1) }}
        </div>

        <div class="flex-1 min-w-0">
            {{-- Header --}}
            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                <span class="text-sm font-semibold text-gray-900">{{ $authorName }}</span>
                @if ($authorRole)
                    <span
                        class="text-xs px-2 py-0.5 rounded-full font-medium {{ $roleBadgeColor }}">{{ $roleLabel }}</span>
                @endif
                @if ($comment->status)
                    <span class="text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 font-medium">
                        Status → {{ \App\Enums\TicketStatus::from($comment->status)->label() }}
                    </span>
                @endif
                @if ($comment->priority)
                    <span class="text-xs px-2 py-0.5 rounded-full bg-orange-100 text-orange-700 font-medium">
                        Prioritas → {{ \App\Enums\TicketPriority::from($comment->priority)->label() }}
                    </span>
                @endif
                <span class="text-xs text-gray-400 ml-auto">{{ $comment->created_at->diffForHumans() }}</span>
            </div>

            {{-- Comment Body --}}
            <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $comment->comment }}</p>

            {{-- Comment Attachments --}}
            @if ($comment->attachments && count($comment->attachments) > 0)
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach ($comment->attachments as $att)
                        @php
                            $ext = strtolower(pathinfo($att, PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                            $attachUrl = Storage::url($att);
                        @endphp
                        <div class="rounded-lg border border-gray-200 overflow-hidden inline-block max-w-sm">
                            @if ($isImage)
                                <a href="{{ $attachUrl }}" target="_blank">
                                    <img src="{{ $attachUrl }}" alt="Lampiran"
                                        class="max-h-48 w-auto object-contain cursor-zoom-in hover:opacity-90 transition-opacity">
                                </a>
                            @else
                                <a href="{{ $attachUrl }}" target="_blank" download
                                    class="flex items-center gap-2 px-3 py-2 text-sm text-blue-600 hover:bg-gray-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                        </path>
                                    </svg>
                                    {{ basename($att) }}
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @elseif ($comment->attachment)
                @php
                    $ext = strtolower(pathinfo($comment->attachment, PATHINFO_EXTENSION));
                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                    $attachUrl = Storage::url($comment->attachment);
                @endphp
                <div class="mt-3 rounded-lg border border-gray-200 overflow-hidden inline-block max-w-sm">
                    @if ($isImage)
                        <a href="{{ $attachUrl }}" target="_blank">
                            <img src="{{ $attachUrl }}" alt="Lampiran"
                                class="max-h-48 w-auto object-contain cursor-zoom-in hover:opacity-90 transition-opacity">
                        </a>
                    @else
                        <a href="{{ $attachUrl }}" target="_blank" download
                            class="flex items-center gap-2 px-3 py-2 text-sm text-blue-600 hover:bg-gray-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                </path>
                            </svg>
                            {{ basename($comment->attachment) }}
                        </a>
                    @endif
                </div>
            @endif

            {{-- Reply Button --}}
            @if ($depth < 2)
                <button
                    onclick="openReplyModal({{ $comment->id }}, '{{ addslashes($authorName) }}', '{{ addslashes($comment->comment) }}')"
                    class="mt-2 text-xs text-blue-500 hover:text-blue-700 font-medium flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                    </svg>
                    Balas
                </button>
            @endif

            {{-- Nested Replies --}}
            @if ($comment->replies->isNotEmpty() && $depth < 3)
                <div class="mt-4 space-y-0 divide-y divide-gray-100">
                    @foreach ($comment->replies as $reply)
                        @include('tickets.partials.comment', [
                            'comment' => $reply,
                            'ticket' => $ticket,
                            'depth' => $depth + 1,
                        ])
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

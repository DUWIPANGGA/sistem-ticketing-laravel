@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <a href="{{ route('tickets.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900">Detail Tiket</h1>
                </div>
                <p class="text-gray-500 font-mono pl-8">#{{ $ticket->ticket_number }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                @can('update', $ticket)
                    <a href="{{ route('tickets.edit', $ticket->id) }}"
                        class="inline-flex items-center px-4 py-2 border border-blue-500/50 rounded-xl shadow-sm text-sm font-medium text-blue-600 bg-blue-500/10 hover:bg-blue-500/20 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Edit Tiket
                    </a>
                @endcan
                @can('delete', $ticket)
                    <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" class="inline-block"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket ini secara permanen?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-red-500/50 rounded-xl shadow-sm text-sm font-medium text-red-600 bg-red-500/10 hover:bg-red-500/20 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            Hapus
                        </button>
                    </form>
                @endcan
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-500/10 border border-green-500/20 text-green-600 px-4 py-3 rounded-xl flex items-center shadow-lg"
                role="alert">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-500/10 border border-red-500/20 text-red-600 px-4 py-3 rounded-xl flex items-center"
                role="alert">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Ticket Content --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Ticket Body --}}
                <div class="bg-white border border-gray-200/50 rounded-2xl shadow-xl overflow-hidden">
                    <div class="h-1 bg-gradient-to-r from-blue-600 to-indigo-600"></div>
                    <div class="p-6 md:p-8">
                        <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 leading-tight flex-1">{{ $ticket->subject }}</h2>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border
                                {{ match ($ticket->status) {
                                    'open' => 'bg-yellow-500/10 text-yellow-600 border-yellow-500/20',
                                    'in_progress' => 'bg-blue-500/10 text-blue-600 border-blue-500/20',
                                    'on_hold' => 'bg-orange-500/10 text-orange-500 border-orange-500/20',
                                    'resolved' => 'bg-green-500/10 text-green-600 border-green-500/20',
                                    'closed' => 'bg-red-500/10 text-red-600 border-red-500/20',
                                    default => 'bg-gray-500/10 text-gray-500 border-gray-500/20',
                                } }}">
                                    {{ \App\Enums\TicketStatus::from($ticket->status)->label() }}
                                </span>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border
                                {{ match ($ticket->priority) {
                                    'low' => 'bg-green-500/10 text-green-600 border-green-500/20',
                                    'medium' => 'bg-yellow-500/10 text-yellow-600 border-yellow-500/20',
                                    'high' => 'bg-orange-500/10 text-orange-500 border-orange-500/20',
                                    'urgent' => 'bg-red-500/10 text-red-600 border-red-500/20',
                                    default => 'bg-gray-500/10 text-gray-500 border-gray-500/20',
                                } }}">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-2
                                    {{ match ($ticket->priority) {
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

                        <div class="prose max-w-none text-gray-700 mb-8 whitespace-pre-wrap text-sm leading-relaxed">
                            {{ $ticket->description }}</div>

                        {{-- Attachments with Preview --}}
                        @if ($ticket->attachments && count($ticket->attachments) > 0)
                            <div class="mt-6 space-y-4">
                                @foreach ($ticket->attachments as $att)
                                    @php
                                        $ext = strtolower(pathinfo($att, PATHINFO_EXTENSION));
                                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                                        $attachUrl = Storage::url($att);
                                    @endphp

                                    <div class="rounded-xl border border-gray-200/50 overflow-hidden bg-gray-50/50">
                                        <div
                                            class="px-4 py-3 border-b border-gray-200/50 flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                                    </path>
                                                </svg>
                                                <span class="text-sm font-medium text-gray-800">Bukti / Lampiran</span>
                                                <span class="text-xs text-gray-400">{{ basename($att) }}</span>
                                            </div>
                                            <a href="{{ $attachUrl }}" target="_blank" download
                                                class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                                    </path>
                                                </svg>
                                                Download
                                            </a>
                                        </div>
                                        @if ($isImage)
                                            <div class="p-4">
                                                <a href="{{ $attachUrl }}" target="_blank">
                                                    <img src="{{ $attachUrl }}" alt="Lampiran tiket"
                                                        class="max-h-96 w-auto rounded-lg mx-auto object-contain shadow-md border border-gray-200 cursor-zoom-in hover:opacity-90 transition-opacity">
                                                </a>
                                                <p class="text-xs text-center text-gray-400 mt-2">Klik gambar untuk membuka
                                                    di tab baru</p>
                                            </div>
                                        @else
                                            <div class="p-4 flex items-center gap-3">
                                                <div
                                                    class="w-10 h-10 rounded-lg bg-indigo-500/20 flex items-center justify-center text-indigo-600 text-xs font-bold uppercase">
                                                    {{ $ext }}</div>
                                                <span class="text-sm text-gray-600">{{ basename($att) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @elseif ($ticket->attachment)
                            @php
                                $ext = strtolower(pathinfo($ticket->attachment, PATHINFO_EXTENSION));
                                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                                $attachUrl = Storage::url($ticket->attachment);
                            @endphp

                            <div class="mt-6 rounded-xl border border-gray-200/50 overflow-hidden bg-gray-50/50">
                                <div class="px-4 py-3 border-b border-gray-200/50 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                            </path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-800">Bukti / Lampiran</span>
                                        <span class="text-xs text-gray-400">{{ basename($ticket->attachment) }}</span>
                                    </div>
                                    <a href="{{ $attachUrl }}" target="_blank" download
                                        class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        Download
                                    </a>
                                </div>
                                @if ($isImage)
                                    <div class="p-4">
                                        <a href="{{ $attachUrl }}" target="_blank">
                                            <img src="{{ $attachUrl }}" alt="Lampiran tiket"
                                                class="max-h-96 w-auto rounded-lg mx-auto object-contain shadow-md border border-gray-200 cursor-zoom-in hover:opacity-90 transition-opacity">
                                        </a>
                                        <p class="text-xs text-center text-gray-400 mt-2">Klik gambar untuk membuka di tab
                                            baru</p>
                                    </div>
                                @else
                                    <div class="p-4 flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-lg bg-indigo-500/20 flex items-center justify-center text-indigo-600 text-xs font-bold uppercase">
                                            {{ $ext }}</div>
                                        <span class="text-sm text-gray-600">{{ basename($ticket->attachment) }}</span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Comments Section --}}
                <div class="bg-white border border-gray-200/50 rounded-2xl shadow-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                        <h3 class="text-lg font-bold text-gray-900">Komentar & Diskusi</h3>
                        <span class="ml-auto text-sm text-gray-400">{{ $comments->count() }} komentar</span>
                    </div>

                    {{-- Comment List --}}
                    <div class="divide-y divide-gray-100">
                        @forelse ($comments as $comment)
                            @include('tickets.partials.comment', [
                                'comment' => $comment,
                                'ticket' => $ticket,
                                'depth' => 0,
                            ])
                        @empty
                            <div class="px-6 py-10 text-center">
                                <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                                <p class="text-gray-400 text-sm">Belum ada komentar. Jadilah yang pertama!</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Add Comment Form --}}
                    <div class="p-6 bg-gray-50/50 border-t border-gray-100">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Tambah Komentar</h4>
                        <form action="{{ route('tickets.addUpdate', $ticket->id) }}" method="POST"
                            enctype="multipart/form-data" class="space-y-3" id="mainCommentForm">
                            @csrf
                            <textarea name="comment" id="mainCommentTextarea" rows="3" @if (!in_array(Auth::user()->role, ['admin', 'technician'])) required @endif
                                placeholder="Tulis komentar Anda di sini..."
                                class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all text-sm resize-y">{{ old('comment') }}</textarea>
                            @error('comment')
                                <p class="text-red-600 text-xs">{{ $message }}</p>
                            @enderror

                            {{-- Admin/Tech extra controls --}}
                            @if (in_array(Auth::user()->role, ['admin', 'technician']))
                                <div
                                    class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-3 bg-white rounded-xl border border-gray-200">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Ubah Status</label>
                                        <select name="status" id="statusSelect" onchange="toggleCommentRequired()"
                                            class="block w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50 text-sm appearance-none">
                                            <option value="">Tidak Diubah</option>
                                            @foreach ($statuses as $status)
                                                <option value="{{ $status->value }}"
                                                    {{ old('status') == $status->value ? 'selected' : '' }}>
                                                    {{ $status->label() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Ubah Prioritas</label>
                                        <select name="priority" id="prioritySelect" onchange="toggleCommentRequired()"
                                            class="block w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50 text-sm appearance-none">
                                            <option value="">Tidak Diubah</option>
                                            @foreach ($priorities as $priority)
                                                <option value="{{ $priority->value }}"
                                                    {{ old('priority') == $priority->value ? 'selected' : '' }}>
                                                    {{ $priority->label() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-center gap-3">
                                <label
                                    class="flex items-center gap-2 text-sm text-gray-500 cursor-pointer hover:text-gray-700 border border-gray-200 rounded-lg px-3 py-2 bg-white hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                        </path>
                                    </svg>
                                    <span id="mainFileLabel">Lampirkan File</span>
                                    <input type="file" name="attachments[]" multiple class="hidden"
                                        onchange="document.getElementById('mainFileLabel').textContent = (this.files.length > 1 ? this.files.length + ' files' : (this.files[0]?.name || 'Lampirkan File'))">
                                </label>
                                <button type="submit"
                                    class="ml-auto inline-flex items-center px-5 py-2.5 border border-transparent rounded-xl shadow-lg shadow-blue-500/25 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:-translate-y-0.5">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    Kirim
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Rating Section --}}
                @if (in_array($ticket->status, ['resolved', 'closed']))
                    <div class="bg-white border border-gray-200/50 rounded-2xl shadow-xl overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <h3 class="text-lg font-bold text-gray-900">Rating & Feedback</h3>
                        </div>
                        <div class="p-6">
                            @if ($ticket->rating)
                                <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-200/50">
                                    <div class="flex items-center gap-1 mb-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= $ticket->rating ? 'text-yellow-500' : 'text-gray-300' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                        @endfor
                                        <span class="ml-2 text-sm font-bold text-gray-900">{{ $ticket->rating }} /
                                            5</span>
                                    </div>
                                    @if ($ticket->feedback)
                                        <p class="text-gray-600 text-sm italic">"{{ $ticket->feedback }}"</p>
                                    @endif
                                </div>
                            @elseif(Auth::id() === $ticket->user_id)
                                <form action="{{ route('tickets.rate', $ticket->id) }}" method="POST"
                                    class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Beri Nilai Layanan
                                            (1-5)</label>
                                        <div class="flex gap-2">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <label class="cursor-pointer">
                                                    <input type="radio" name="rating" value="{{ $i }}"
                                                        class="sr-only peer" required>
                                                    <div
                                                        class="w-10 h-10 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-500 peer-checked:bg-yellow-400/20 peer-checked:text-yellow-600 peer-checked:border-yellow-400 hover:bg-gray-100 transition-colors font-medium text-sm">
                                                        {{ $i }}</div>
                                                </label>
                                            @endfor
                                        </div>
                                    </div>
                                    <div>
                                        <label for="feedback"
                                            class="block text-sm font-medium text-gray-700 mb-1">Feedback <span
                                                class="text-xs text-gray-400">(Opsional)</span></label>
                                        <textarea name="feedback" id="feedback" rows="3"
                                            class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-yellow-500/50 focus:border-yellow-500 text-sm resize-y"
                                            placeholder="Ceritakan pengalaman Anda..."></textarea>
                                    </div>
                                    <button type="submit"
                                        class="px-6 py-2 bg-gradient-to-r from-yellow-500 to-yellow-400 hover:from-yellow-400 hover:to-yellow-300 text-white font-medium rounded-xl shadow-lg shadow-yellow-500/20 text-sm">Kirim
                                        Feedback</button>
                                </form>
                            @else
                                <p class="text-sm text-gray-400 italic">Pengguna belum memberikan penilaian.</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar Info --}}
            <div class="space-y-6">
                <div class="bg-white border border-gray-200/50 rounded-2xl shadow-xl overflow-hidden p-6">
                    <h3
                        class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">
                        Informasi Tiket</h3>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase">Kategori</dt>
                            <dd
                                class="mt-1 text-sm font-semibold text-gray-900 bg-indigo-50 inline-block px-3 py-1 rounded-lg">
                                {{ $ticket->category }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase mb-1.5">Dibuat Oleh</dt>
                            <dd class="text-sm text-gray-700 flex items-center gap-2">
                                <div
                                    class="w-7 h-7 rounded-full bg-blue-500/20 text-blue-600 flex items-center justify-center font-bold text-xs uppercase">
                                    {{ substr($ticket->creator->name ?? '?', 0, 1) }}</div>
                                {{ $ticket->creator->name ?? 'N/A' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase mb-1.5">Ditugaskan Ke</dt>
                            <dd class="text-sm text-gray-700 flex items-center gap-2">
                                @if ($ticket->assignee)
                                    <div
                                        class="w-7 h-7 rounded-full bg-indigo-500/20 text-indigo-600 flex items-center justify-center font-bold text-xs uppercase">
                                        {{ substr($ticket->assignee->name, 0, 1) }}</div>
                                    {{ $ticket->assignee->name }}
                                @else
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full text-xs italic">Belum
                                        Ditugaskan</span>
                                @endif
                            </dd>
                        </div>
                        <hr class="border-gray-100">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <dt class="text-xs font-medium text-gray-500">Dibuat</dt>
                                <dd class="mt-1 text-xs text-gray-700">{{ $ticket->created_at->format('d M Y') }}<br><span
                                        class="text-gray-400">{{ $ticket->created_at->format('H:i') }}</span></dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500">Diperbarui</dt>
                                <dd class="mt-1 text-xs text-gray-700">{{ $ticket->updated_at->format('d M Y') }}<br><span
                                        class="text-gray-400">{{ $ticket->updated_at->format('H:i') }}</span></dd>
                            </div>
                        </div>
                    </dl>
                </div>

                {{-- Cannotable notice for user --}}
                @if (Auth::user()->role === 'user')
                    @if (!is_null($ticket->assigned_to) || $ticket->status !== 'open')
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-amber-800">Tiket Tidak Dapat Diedit</p>
                                    <p class="text-xs text-amber-600 mt-1">
                                        @if (!is_null($ticket->assigned_to))
                                            Tiket sudah ditugaskan ke teknisi.
                                        @else
                                            Status tiket sudah berubah.
                                        @endif
                                        Hubungi admin jika perlu perubahan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    {{-- Reply Modal --}}
    <div id="replyModal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-900">Balas Komentar</h3>
                <button onclick="closeReplyModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div id="replyingToBox"
                    class="mb-4 p-3 bg-gray-50 rounded-xl border-l-4 border-blue-400 text-sm text-gray-600 hidden">
                    <span class="font-medium text-gray-700">Membalas:</span>
                    <span id="replyingToText" class="italic"></span>
                </div>
                <form id="replyForm" action="{{ route('tickets.addUpdate', $ticket->id) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="parent_id" id="replyParentId">
                    <textarea name="comment" rows="4" required placeholder="Tulis balasan..."
                        class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 text-sm resize-y"></textarea>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeReplyModal()"
                            class="px-4 py-2 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">Batal</button>
                        <button type="submit"
                            class="px-5 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl text-sm font-medium hover:from-blue-500 hover:to-indigo-500 transition-all shadow-lg shadow-blue-500/25">Kirim
                            Balasan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openReplyModal(commentId, authorName, commentText) {
            document.getElementById('replyParentId').value = commentId;
            document.getElementById('replyingToText').textContent = ' ' + authorName + ': "' + commentText.substring(0,
                80) + (commentText.length > 80 ? '...' : '') + '"';
            document.getElementById('replyingToBox').classList.remove('hidden');
            document.getElementById('replyModal').classList.remove('hidden');
            document.getElementById('replyModal').querySelector('textarea').focus();
        }

        function closeReplyModal() {
            document.getElementById('replyModal').classList.add('hidden');
            document.getElementById('replyParentId').value = '';
        }

        document.getElementById('replyModal').addEventListener('click', function(e) {
            if (e.target === this) closeReplyModal();
        });

        // Toggle required on comment textarea for admin/tech when status or priority is selected
        function toggleCommentRequired() {
            const statusVal = document.getElementById('statusSelect')?.value || '';
            const priorityVal = document.getElementById('prioritySelect')?.value || '';
            const textarea = document.getElementById('mainCommentTextarea');
            if (!textarea) return;

            if (statusVal || priorityVal) {
                // Status or priority is selected — comment not required
                textarea.removeAttribute('required');
                textarea.placeholder = 'Komentar opsional jika hanya mengubah status/prioritas...';
            } else {
                // Nothing selected — comment is required
                textarea.setAttribute('required', 'required');
                textarea.placeholder = 'Tulis komentar Anda di sini...';
            }
        }
    </script>
@endsection

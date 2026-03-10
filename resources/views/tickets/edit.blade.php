@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Ticket</h1>
                <p class="text-gray-500 mt-1 text-sm">#{{ $ticket->ticket_number }}</p>
            </div>
            <a href="{{ route('tickets.show', $ticket->id) }}"
                class="inline-flex items-center text-gray-500 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Back to Ticket
            </a>
        </div>

        <div class="bg-white border border-gray-200/50 rounded-2xl shadow-xl overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-indigo-600 to-purple-600"></div>
            <div class="p-8">
                <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1.5">Subject <span
                                class="text-red-600">*</span></label>
                        <input type="text" name="subject" id="subject" value="{{ old('subject', $ticket->subject) }}"
                            required
                            class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all shadow-inner sm:text-sm">
                        @error('subject')
                            <p class="text-red-600 text-xs mt-1.5 flex items-center"><svg class="w-4 h-4 mr-1" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Description <span
                                class="text-red-600">*</span></label>
                        <textarea name="description" id="description" rows="6" required
                            class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all shadow-inner sm:text-sm resize-y">{{ old('description', $ticket->description) }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-xs mt-1.5 flex items-center"><svg class="w-4 h-4 mr-1" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1.5">Category <span
                                    class="text-red-600">*</span></label>
                            <select name="category" id="category" required
                                class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all shadow-inner sm:text-sm appearance-none">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->name }}"
                                        {{ old('category', $ticket->category) == $category->name ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 top-[2.5rem] flex items-center px-4 text-gray-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            @error('category')
                                <p class="text-red-600 text-xs mt-1.5 flex items-center"><svg class="w-4 h-4 mr-1"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>{{ $message }}</p>
                            @enderror
                        </div>

                        @if (Auth::user()->role === 'admin')
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1.5">Priority <span
                                        class="text-red-600">*</span></label>
                                <select name="priority" id="priority" required
                                    class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all shadow-inner sm:text-sm appearance-none">
                                    @foreach ($priorities as $priority)
                                        <option value="{{ $priority->value }}"
                                            {{ old('priority', $ticket->priority) == $priority->value ? 'selected' : '' }}>
                                            {{ $priority->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 top-[2.5rem] flex items-center px-4 text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                                @error('priority')
                                    <p class="text-red-600 text-xs mt-1.5 flex items-center"><svg class="w-4 h-4 mr-1"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </div>

                    @if (Auth::user()->role === 'admin')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status <span
                                        class="text-red-600">*</span></label>
                                <select name="status" id="status" required
                                    class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all shadow-inner sm:text-sm appearance-none">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->value }}"
                                            {{ old('status', $ticket->status) == $status->value ? 'selected' : '' }}>
                                            {{ $status->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 top-[2.5rem] flex items-center px-4 text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                                @error('status')
                                    <p class="text-red-600 text-xs mt-1.5 flex items-center"><svg class="w-4 h-4 mr-1"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-1.5">Assign
                                    To</label>
                                <select name="assigned_to" id="assigned_to"
                                    class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all shadow-inner sm:text-sm appearance-none">
                                    <option value="">-- Unassigned --</option>
                                    @foreach (\App\Models\User::all() as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('assigned_to', $ticket->assigned_to) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 top-[2.5rem] flex items-center px-4 text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                                @error('assigned_to')
                                    <p class="text-red-600 text-xs mt-1.5 flex items-center"><svg class="w-4 h-4 mr-1"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div>
                                <label for="attachments" class="block text-sm font-medium text-gray-700 mb-1.5">Replace
                                    Attachments <span class="text-gray-500 text-xs font-normal">(Optional, multiple
                                        allowed. This will replace the previous files)</span></label>
                                <div class="mt-1 flex items-center gap-4 flex-wrap">
                                    <label
                                        class="px-4 py-2 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-100 focus-within:ring-2 focus-within:ring-indigo-500 cursor-pointer transition-colors">
                                        <span>Choose Files</span>
                                        <input id="attachments" name="attachments[]" type="file" multiple
                                            class="sr-only">
                                    </label>
                                    @if ($ticket->attachments && count($ticket->attachments) > 0)
                                        @foreach ($ticket->attachments as $att)
                                            <div class="text-sm text-gray-500 flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                                    </path>
                                                </svg>
                                                <span class="truncate max-w-[150px] inline-block align-bottom"
                                                    title="{{ basename($att) }}">{{ basename($att) }}</span>
                                            </div>
                                        @endforeach
                                    @elseif ($ticket->attachment)
                                        <div class="text-sm text-gray-500 flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                                </path>
                                            </svg>
                                            <span class="truncate max-w-[150px] inline-block align-bottom"
                                                title="{{ basename($ticket->attachment) }}">{{ basename($ticket->attachment) }}</span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500">No file chosen</span>
                                    @endif
                                </div>
                                @error('attachments')
                                    <p class="text-red-600 text-xs mt-1.5 flex items-center"><svg class="w-4 h-4 mr-1"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>{{ $message }}</p>
                                @enderror
                                @error('attachments.*')
                                    <p class="text-red-600 text-xs mt-1.5 flex items-center"><svg class="w-4 h-4 mr-1"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="pt-4">
                            <label for="comment" class="block text-sm font-medium text-gray-700 mb-1.5">Add an update
                                note <span class="text-gray-500 text-xs font-normal">(Optional, will be added to
                                    history)</span></label>
                            <textarea name="comment" id="comment" rows="3" placeholder="Explain the changes made in this edit..."
                                class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all shadow-inner sm:text-sm resize-y">{{ old('comment') }}</textarea>
                            @error('comment')
                                <p class="text-red-600 text-xs mt-1.5 flex items-center"><svg class="w-4 h-4 mr-1"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-6 border-t border-gray-200/50 flex justify-end gap-4">
                            <a href="{{ route('tickets.show', $ticket->id) }}"
                                class="px-6 py-2.5 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-gray-500 transition-colors">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-6 py-2.5 border border-transparent rounded-xl shadow-lg shadow-indigo-500/30 text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-indigo-500 transition-all transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save Changes
                            </button>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection

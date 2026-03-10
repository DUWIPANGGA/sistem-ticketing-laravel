@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Create New Ticket</h1>
                <p class="text-gray-500 mt-1 text-sm">Fill in the details below to open a new support request.</p>
            </div>
            <a href="{{ route('tickets.index') }}"
                class="inline-flex items-center text-gray-500 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Back to Tickets
            </a>
        </div>

        <div class="bg-white border border-gray-200/50 rounded-2xl shadow-xl overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-blue-600 to-indigo-600"></div>
            <div class="p-8">
                <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1.5">Subject <span
                                class="text-red-600">*</span></label>
                        <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                            placeholder="Brief summary of the issue"
                            class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all shadow-inner sm:text-sm">
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
                        <textarea name="description" id="description" rows="6" required placeholder="Provide detailed information..."
                            class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all shadow-inner sm:text-sm resize-y">{{ old('description') }}</textarea>
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
                                class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all shadow-inner sm:text-sm appearance-none">
                                <option value="" disabled selected class="text-gray-500">Select category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->name }}"
                                        {{ old('category') == $category->name ? 'selected' : '' }}>
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

                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-1.5">Priority <span
                                    class="text-red-600">*</span></label>
                            <select name="priority" id="priority" required
                                class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all shadow-inner sm:text-sm appearance-none">
                                <option value="" disabled selected class="text-gray-500">Select priority level
                                </option>
                                @foreach ($priorities as $priority)
                                    <option value="{{ $priority->value }}"
                                        {{ old('priority') == $priority->value ? 'selected' : '' }}>
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
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="attachments" class="block text-sm font-medium text-gray-700 mb-1.5">Attachments
                                <span class="text-gray-500 text-xs font-normal">(Optional, multiple allowed)</span></label>
                            <div
                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 border-dashed rounded-xl bg-gray-50/30 hover:bg-gray-50/50 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-10 w-10 text-gray-500" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-500 justify-center">
                                        <label for="attachments"
                                            class="relative cursor-pointer bg-transparent rounded-md font-medium text-blue-600 hover:text-blue-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-offset-gray-900 focus-within:ring-blue-500">
                                            <span>Upload files</span>
                                            <input id="attachments" name="attachments[]" type="file" multiple
                                                class="sr-only">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                </div>
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

                    <div class="pt-6 border-t border-gray-200/50 flex justify-end gap-4">
                        <a href="{{ route('tickets.index') }}"
                            class="px-6 py-2.5 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-gray-500 transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-6 py-2.5 border border-transparent rounded-xl shadow-lg shadow-blue-500/30 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-500 transition-all transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Create Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

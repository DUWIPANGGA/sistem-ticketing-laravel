@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create New Priority</h1>
            <p class="text-gray-500 mt-1 text-sm">Add a new priority level for tickets.</p>
        </div>
        <a href="{{ route('master-data.priorities.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-900 transition-colors">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Priorities
        </a>
    </div>

    <div class="bg-white border border-gray-200/50 rounded-2xl shadow-xl overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-blue-600 to-indigo-600"></div>
        <div class="p-8">
            <form action="{{ route('master-data.priorities.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Name <span class="text-red-600">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="e.g. Urgent, High, Medium"
                           class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all shadow-inner sm:text-sm">
                    @error('name')
                        <p class="text-red-600 text-xs mt-1.5 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="value" class="block text-sm font-medium text-gray-700 mb-1.5">Value <span class="text-red-600">*</span></label>
                    <input type="text" name="value" id="value" value="{{ old('value') }}" required placeholder="e.g. urgent, high, medium"
                           class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all shadow-inner sm:text-sm">
                    <p class="text-gray-500 text-xs mt-1">Internal key used in the database. Use lowercase, no spaces.</p>
                    @error('value')
                        <p class="text-red-600 text-xs mt-1.5 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="sla_hours" class="block text-sm font-medium text-gray-700 mb-1.5">SLA Hours <span class="text-red-600">*</span></label>
                        <input type="number" name="sla_hours" id="sla_hours" value="{{ old('sla_hours', 24) }}" required min="1"
                               class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all shadow-inner sm:text-sm">
                        @error('sla_hours')
                            <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1.5">Sort Order <span class="text-red-600">*</span></label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" required min="0"
                               class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all shadow-inner sm:text-sm">
                        @error('sort_order')
                            <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-200/50 flex justify-end gap-4">
                    <a href="{{ route('master-data.priorities.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-gray-500 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-2.5 border border-transparent rounded-xl shadow-lg shadow-blue-500/30 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-500 transition-all transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Create Priority
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create Article</h1>
            <p class="text-gray-500 mt-1 text-sm">Add a new guide to the knowledge base.</p>
        </div>
        <a href="{{ route('knowledge-base.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-900 transition-colors">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Articles
        </a>
    </div>

    <div class="bg-white border border-gray-200/50 rounded-2xl shadow-xl overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-blue-600 to-indigo-600"></div>
        <div class="p-8">
            <form action="{{ route('knowledge-base.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">Article Title <span class="text-red-600">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required placeholder="E.g. How to reset your password"
                           class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all shadow-inner sm:text-sm">
                    @error('title')
                        <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1.5">Category <span class="text-gray-500 text-xs font-normal">(Optional)</span></label>
                    <div class="relative">
                        <select name="category" id="category"
                                class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all shadow-inner sm:text-sm appearance-none">
                            <option value="">-- Select Category --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->name }}" {{ old('category') == $category->name ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                           <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    @error('category')
                        <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1.5">Content <span class="text-red-600">*</span></label>
                    <textarea name="content" id="content" rows="10" required placeholder="Write your article content here..."
                              class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all shadow-inner sm:text-sm resize-y">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center mb-4">
                    <input id="is_published" name="is_published" type="checkbox" value="1" {{ old('is_published', true) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                    <label for="is_published" class="ml-2 text-sm font-medium text-gray-700">Publish Article</label>
                </div>
                <p class="text-xs text-gray-500 ml-6 -mt-3">If unchecked, the article will be saved as a draft.</p>

                <div class="pt-6 border-t border-gray-200/50 flex justify-end gap-4">
                    <a href="{{ route('knowledge-base.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-100 focus:outline-none transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-2.5 border border-transparent rounded-xl shadow-lg shadow-blue-500/30 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-500 transition-all transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Create Article
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

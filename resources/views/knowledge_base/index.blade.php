@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Knowledge Base</h1>
        <p class="text-gray-500 mt-1 text-sm">Find answers, guides, and informative articles.</p>
    </div>
    @if(in_array(Auth::user()->role, ['admin', 'technician']))
    <a href="{{ route('knowledge-base.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-xl shadow-lg shadow-blue-500/25 text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-500 transition-all transform hover:-translate-y-0.5">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Create Article
    </a>
    @endif
</div>

@if (session('success'))
    <div class="bg-green-500/10 border border-green-500/20 text-green-600 px-4 py-3 rounded-xl mb-6 flex items-center shadow-lg backdrop-blur-sm" role="alert">
        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="block sm:inline font-medium">{{ session('success') }}</span>
    </div>
@endif

<!-- Search Form -->
<form action="{{ route('knowledge-base.index') }}" method="GET" class="mb-8 relative">
    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
        <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
    </div>
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search articles..." 
           class="block w-full pl-11 pr-4 py-4 border border-gray-200/50 rounded-2xl bg-white/80 backdrop-blur-md text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 shadow-xl text-lg transition-all">
</form>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse ($articles as $article)
        <div class="bg-white border border-gray-200/50 rounded-2xl p-6 shadow-xl hover:border-blue-500/50 transition-colors flex flex-col">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-3 mt-1 text-sm">
                    @if($article->category)
                        <span class="bg-blue-500/10 text-blue-600 px-2.5 py-1 rounded-md text-xs font-semibold">{{ $article->category }}</span>
                    @endif
                    @if(in_array(Auth::user()->role, ['admin', 'technician']) && !$article->is_published)
                        <span class="bg-yellow-500/10 text-yellow-600 px-2.5 py-1 rounded-md text-xs font-semibold border border-yellow-500/20">Draft</span>
                    @endif
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">
                    <a href="{{ route('knowledge-base.show', $article->id) }}" class="hover:text-blue-600 transition-colors">{{ $article->title }}</a>
                </h3>
                <p class="text-gray-500 line-clamp-3 mb-4 text-sm">{{ Str::limit(strip_tags($article->content), 120) }}</p>
            </div>
            <div class="border-t border-gray-200/50 pt-4 mt-auto flex items-center justify-between text-xs text-gray-500">
                <span>By {{ $article->author->name ?? 'Unknown' }}</span>
                <span>{{ $article->created_at->format('M j, Y') }}</span>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white/50 border border-gray-200/50 rounded-2xl p-12 text-center">
            <div class="w-16 h-16 bg-gray-50/50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No articles found</h3>
            <p class="text-gray-500 text-sm mb-4">Try searching for something else or ask support for help.</p>
        </div>
    @endforelse
</div>

<div class="mt-8">
    {{ $articles->links() }}
</div>
@endsection

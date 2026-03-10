@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Knowledge Base Article</h1>
            <p class="text-gray-500 mt-1 text-sm">Read and learn from this guide.</p>
        </div>
        <a href="{{ route('knowledge-base.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-900 transition-colors">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Articles
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-500/10 border border-green-500/20 text-green-600 px-4 py-3 rounded-xl mb-6 flex items-center shadow-lg backdrop-blur-sm" role="alert">
            <span class="block sm:inline font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white border border-gray-200/50 rounded-2xl shadow-xl overflow-hidden">
        <div class="p-8 md:p-12">
            <div class="flex gap-2 mb-4">
               @if($article->category)
                   <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border bg-blue-500/10 text-blue-600 border-blue-500/20">
                       {{ $article->category }}
                   </span>
               @endif
               @if(!$article->is_published)
                   <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border bg-yellow-500/10 text-yellow-600 border-yellow-500/20">
                       Draft
                   </span>
               @endif
            </div>

            <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 mb-6 tracking-tight">{{ $article->title }}</h1>
            
            <div class="flex items-center gap-4 text-sm text-gray-500 border-b border-gray-200/50 pb-8 mb-8">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center font-bold text-gray-900">
                        {{ substr($article->author->name ?? 'A', 0, 1) }}
                    </div>
                    <span>By <strong class="text-gray-700">{{ $article->author->name ?? 'Unknown Author' }}</strong></span>
                </div>
                <span>•</span>
                <span>Published {{ $article->created_at->format('M j, Y') }}</span>
            </div>

            <div class="prose  prose-blue max-w-none text-gray-700 text-lg leading-relaxed">
                {!! nl2br(e($article->content)) !!}
            </div>
            
            @if(in_array(Auth::user()->role, ['admin', 'technician']))
            <div class="mt-12 pt-6 border-t border-gray-200/50 flex items-center gap-4">
                <a href="{{ route('knowledge-base.edit', $article->id) }}" class="inline-flex items-center px-4 py-2 border border-blue-500/50 text-sm font-medium rounded-xl shadow-sm text-blue-600 bg-blue-500/10 hover:bg-blue-500/20 focus:outline-none transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit Article
                </a>
                <form action="{{ route('knowledge-base.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this article?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-500/50 text-sm font-medium rounded-xl shadow-sm text-red-600 bg-red-500/10 hover:bg-red-500/20 focus:outline-none transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Delete
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

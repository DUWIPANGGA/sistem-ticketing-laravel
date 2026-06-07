@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Knowledge Base</h1>
        <p class="text-gray-500 mt-1 text-sm">Manage knowledge base articles.</p>
    </div>
    <a href="{{ route('master-data.knowledge-base.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-xl shadow-lg shadow-blue-500/25 text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-500 transition-all transform hover:-translate-y-0.5">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Create Article
    </a>
</div>

@if (session('success'))
    <div class="bg-green-500/10 border border-green-500/20 text-green-600 px-4 py-3 rounded-xl mb-6 flex items-center shadow-lg backdrop-blur-sm" role="alert">
        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="block sm:inline font-medium">{{ session('success') }}</span>
    </div>
@endif

<div class="bg-white border border-gray-200/50 rounded-2xl overflow-hidden shadow-xl">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-700/50">
            <thead class="bg-gray-50/50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Title</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Author</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700/50 relative">
                @forelse ($articles as $article)
                    <tr class="hover:bg-gray-100/25 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <a href="{{ route('master-data.knowledge-base.show', $article->id) }}" class="hover:text-blue-600 transition-colors">{{ $article->title }}</a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $article->category ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $article->author->name ?? 'Unknown' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($article->is_published)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border bg-green-500/10 text-green-600 border-green-500/20">Published</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border bg-yellow-500/10 text-yellow-600 border-yellow-500/20">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $article->created_at->format('M j, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                            <a href="{{ route('master-data.knowledge-base.edit', $article->id) }}" class="text-indigo-600 hover:text-indigo-300 transition-colors" title="Edit">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('master-data.knowledge-base.destroy', $article->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this article?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-300 transition-colors" title="Delete">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <h3 class="text-lg font-medium text-gray-900 mb-1">No articles found</h3>
                                <p class="text-gray-500 text-sm">Get started by creating a new article.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $articles->links() }}
</div>
@endsection

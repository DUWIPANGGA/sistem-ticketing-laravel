<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeBaseArticle;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class KnowledgeBaseArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = KnowledgeBaseArticle::query();

        if (Auth::user()->role === 'user') {
            $query->where('is_published', true);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'ilike', "%{$search}%")
                  ->orWhere('content', 'ilike', "%{$search}%");
        }

        $articles = $query->latest()->paginate(12)->withQueryString();

        return view('knowledge_base.index', compact('articles'));
    }

    public function create()
    {
        if (Auth::user()->role === 'user') abort(403);
        $categories = \App\Models\Category::all();
        return view('knowledge_base.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role === 'user') abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
            'is_published' => 'boolean'
        ]);

        KnowledgeBaseArticle::create([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'is_published' => $request->has('is_published'),
            'author_id' => Auth::id(),
        ]);

        return redirect()->route('knowledge-base.index')->with('success', 'Article created successfully.');
    }

    public function show($id)
    {
        $article = KnowledgeBaseArticle::findOrFail($id);
        
        if (!$article->is_published && Auth::user()->role === 'user') {
            abort(403);
        }

        return view('knowledge_base.show', compact('article'));
    }

    public function edit($id)
    {
        if (Auth::user()->role === 'user') abort(403);
        $article = KnowledgeBaseArticle::findOrFail($id);
        $categories = \App\Models\Category::all();
        return view('knowledge_base.edit', compact('article', 'categories'));
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role === 'user') abort(403);
        
        $article = KnowledgeBaseArticle::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
            'is_published' => 'boolean'
        ]);

        $article->update([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('knowledge-base.show', $article->id)->with('success', 'Article updated successfully.');
    }

    public function destroy($id)
    {
        if (Auth::user()->role === 'user') abort(403);
        $article = KnowledgeBaseArticle::findOrFail($id);
        $article->delete();
        return redirect()->route('knowledge-base.index')->with('success', 'Article deleted successfully.');
    }
}

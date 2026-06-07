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
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $articles = $query->latest()->paginate(12)->withQueryString();

        return view('knowledge_base.index', compact('articles'));
    }

    public function show($id)
    {
        $article = KnowledgeBaseArticle::findOrFail($id);

        if (!$article->is_published && Auth::user()->role === 'user') {
            abort(403);
        }

        return view('knowledge_base.show', compact('article'));
    }
}

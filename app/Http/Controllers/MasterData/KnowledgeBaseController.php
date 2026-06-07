<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeBaseArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KnowledgeBaseController extends Controller
{
    public function index()
    {
        $this->requireStaff();
        $articles = KnowledgeBaseArticle::latest()->paginate(12);
        return view('master-data.knowledge-base.index', compact('articles'));
    }

    public function create()
    {
        $this->requireStaff();
        $categories = \App\Models\Category::all();
        return view('master-data.knowledge-base.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->requireStaff();
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

        return redirect()->route('master-data.knowledge-base.index')->with('success', 'Article created successfully.');
    }

    public function edit($id)
    {
        $this->requireStaff();
        $article = KnowledgeBaseArticle::findOrFail($id);
        $categories = \App\Models\Category::all();
        return view('master-data.knowledge-base.edit', compact('article', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $this->requireStaff();
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

        return redirect()->route('master-data.knowledge-base.show', $article->id)->with('success', 'Article updated successfully.');
    }

    public function show($id)
    {
        $this->requireStaff();
        $article = KnowledgeBaseArticle::findOrFail($id);
        return view('master-data.knowledge-base.show', compact('article'));
    }

    public function destroy($id)
    {
        $this->requireStaff();
        $article = KnowledgeBaseArticle::findOrFail($id);
        $article->delete();
        return redirect()->route('master-data.knowledge-base.index')->with('success', 'Article deleted successfully.');
    }
}

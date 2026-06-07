<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $this->requireAdmin();
        $categories = Category::paginate(10);
        return view('master-data.categories.index', compact('categories'));
    }

    public function create()
    {
        $this->requireAdmin();
        return view('master-data.categories.create');
    }

    public function store(Request $request)
    {
        $this->requireAdmin();
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'owner_name' => 'nullable|string|max:255'
        ]);

        Category::create($request->all());

        return redirect()->route('master-data.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        $this->requireAdmin();
        return view('master-data.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $this->requireAdmin();
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'owner_name' => 'nullable|string|max:255'
        ]);

        $category->update($request->all());

        return redirect()->route('master-data.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $this->requireAdmin();
        $category->delete();

        return redirect()->route('master-data.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}

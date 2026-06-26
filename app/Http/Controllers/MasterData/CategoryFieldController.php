<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryField;
use Illuminate\Http\Request;

class CategoryFieldController extends Controller
{
    /**
     * Valid field types.
     */
    private array $validTypes = [
        'text', 'textarea', 'number', 'email',
        'date', 'datetime-local',
        'select', 'radio', 'checkbox', 'file',
    ];

    /**
     * Store a new dynamic field for a category.
     */
    public function store(Request $request, Category $category)
    {
        $this->requireAdmin();

        $validated = $request->validate([
            'label'         => 'required|string|max:255',
            'name'          => ['required', 'string', 'max:255', 'regex:/^[a-z0-9_]+$/'],
            'type'          => 'required|in:' . implode(',', $this->validTypes),
            'is_required'   => 'nullable|boolean',
            'options'       => 'nullable|string|max:2000',
            'placeholder'   => 'nullable|string|max:255',
            'default_value' => 'nullable|string|max:500',
            'sort_order'    => 'nullable|integer|min:0',
            'is_active'     => 'nullable|boolean',
        ], [
            'name.regex' => 'Nama field hanya boleh berisi huruf kecil, angka, dan underscore (_).',
        ]);

        // Default sort_order = next available
        if (! isset($validated['sort_order'])) {
            $validated['sort_order'] = $category->fields()->max('sort_order') + 1;
        }

        $category->fields()->create([
            'label'         => $validated['label'],
            'name'          => $validated['name'],
            'type'          => $validated['type'],
            'is_required'   => $request->has('is_required') ? (bool) $request->is_required : false,
            'options'       => $validated['options'] ?? null,
            'placeholder'   => $validated['placeholder'] ?? null,
            'default_value' => $validated['default_value'] ?? null,
            'sort_order'    => $validated['sort_order'] ?? 0,
            'is_active'     => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        return redirect()->back()->with('success', 'Field dinamis berhasil ditambahkan.');
    }

    /**
     * Update an existing dynamic field.
     */
    public function update(Request $request, CategoryField $field)
    {
        $this->requireAdmin();

        $validated = $request->validate([
            'label'         => 'required|string|max:255',
            'name'          => ['required', 'string', 'max:255', 'regex:/^[a-z0-9_]+$/'],
            'type'          => 'required|in:' . implode(',', $this->validTypes),
            'is_required'   => 'nullable|boolean',
            'options'       => 'nullable|string|max:2000',
            'placeholder'   => 'nullable|string|max:255',
            'default_value' => 'nullable|string|max:500',
            'sort_order'    => 'nullable|integer|min:0',
            'is_active'     => 'nullable|boolean',
        ], [
            'name.regex' => 'Nama field hanya boleh berisi huruf kecil, angka, dan underscore (_).',
        ]);

        $field->update([
            'label'         => $validated['label'],
            'name'          => $validated['name'],
            'type'          => $validated['type'],
            'is_required'   => $request->has('is_required') ? (bool) $request->is_required : false,
            'options'       => $validated['options'] ?? null,
            'placeholder'   => $validated['placeholder'] ?? null,
            'default_value' => $validated['default_value'] ?? null,
            'sort_order'    => $validated['sort_order'] ?? $field->sort_order,
            'is_active'     => $request->has('is_active') ? (bool) $request->is_active : false,
        ]);

        return redirect()->back()->with('success', 'Field dinamis berhasil diperbarui.');
    }

    /**
     * Delete a dynamic field.
     */
    public function destroy(CategoryField $field)
    {
        $this->requireAdmin();

        $field->delete();

        return redirect()->back()->with('success', 'Field dinamis berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Priority;
use Illuminate\Http\Request;

class PriorityController extends Controller
{
    public function index()
    {
        $this->requireAdmin();
        $priorities = Priority::orderBy('sort_order')->paginate(10);
        return view('master-data.priorities.index', compact('priorities'));
    }

    public function create()
    {
        $this->requireAdmin();
        return view('master-data.priorities.create');
    }

    public function store(Request $request)
    {
        $this->requireAdmin();
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|string|max:50|unique:priorities',
            'sla_hours' => 'required|integer|min:1',
            'sort_order' => 'required|integer|min:0',
        ]);

        Priority::create($request->all());

        return redirect()->route('master-data.priorities.index')->with('success', 'Prioritas berhasil ditambahkan.');
    }

    public function edit(Priority $priority)
    {
        $this->requireAdmin();
        return view('master-data.priorities.edit', compact('priority'));
    }

    public function update(Request $request, Priority $priority)
    {
        $this->requireAdmin();
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|string|max:50|unique:priorities,value,' . $priority->id,
            'sla_hours' => 'required|integer|min:1',
            'sort_order' => 'required|integer|min:0',
        ]);

        $priority->update($request->all());

        return redirect()->route('master-data.priorities.index')->with('success', 'Prioritas berhasil diperbarui.');
    }

    public function destroy(Priority $priority)
    {
        $this->requireAdmin();
        $priority->delete();
        return redirect()->route('master-data.priorities.index')->with('success', 'Prioritas berhasil dihapus.');
    }
}

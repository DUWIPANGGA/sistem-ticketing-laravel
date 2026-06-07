<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index()
    {
        $this->requireAdmin();
        $divisions = Division::paginate(10);
        return view('master-data.divisions.index', compact('divisions'));
    }

    public function create()
    {
        $this->requireAdmin();
        return view('master-data.divisions.create');
    }

    public function store(Request $request)
    {
        $this->requireAdmin();
        $request->validate([
            'name' => 'required|string|max:255|unique:divisions',
            'description' => 'nullable|string',
        ]);

        Division::create($request->all());

        return redirect()->route('master-data.divisions.index')->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function edit(Division $division)
    {
        $this->requireAdmin();
        return view('master-data.divisions.edit', compact('division'));
    }

    public function update(Request $request, Division $division)
    {
        $this->requireAdmin();
        $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name,' . $division->id,
            'description' => 'nullable|string',
        ]);

        $division->update($request->all());

        return redirect()->route('master-data.divisions.index')->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy(Division $division)
    {
        $this->requireAdmin();
        $division->delete();
        return redirect()->route('master-data.divisions.index')->with('success', 'Divisi berhasil dihapus.');
    }
}

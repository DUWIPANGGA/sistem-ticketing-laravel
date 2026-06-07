<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $this->requireAdmin();
        $users = User::with('division')->paginate(15);
        return view('master-data.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $this->requireAdmin();
        $divisions = Division::orderBy('name')->get();
        return view('master-data.users.edit', compact('user', 'divisions'));
    }

    public function update(Request $request, User $user)
    {
        $this->requireAdmin();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'technician', 'user'])],
            'division_id' => 'nullable|exists:divisions,id',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'division_id' => $request->division_id,
        ]);

        return redirect()->route('master-data.users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        $this->requireAdmin();
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete yourself!');
        }
        $user->delete();
        return redirect()->route('master-data.users.index')->with('success', 'User deleted successfully!');
    }
}

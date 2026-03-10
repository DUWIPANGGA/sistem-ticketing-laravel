<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $users = User::paginate(15);
        return view('users.index', compact('users'));
    }

    public function edit(User $user)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'technician', 'user'])],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete yourself!');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}

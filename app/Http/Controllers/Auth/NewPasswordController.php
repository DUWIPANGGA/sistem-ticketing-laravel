<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): \Illuminate\Contracts\View\View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', Rules\Password::defaults(), 'confirmed'],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will fade out the light login form and show the success article.
        $status = Password::reset(
            $request->only('email', 'password', 'token'),
            function ($user) use ($request) {
                $user->password = Hash::make($request->password);
                $user->save();

                // After a successful password reset, we can clear the session
                // and log the user into their new password.
                Auth::login($user);
            }
        );

        // If the password reset was a failure, we may have the session data
        // cleared out. We will re-authenticate the user with the old password.
        if ($status !== Password::PASSWORD_RESET) {
            return back()->withInput($request->only('email'))->withErrors([
                'email' => trans($status),
            ]);
        }

        return redirect()->route('dashboard')->with('status', trans($status));
    }
}
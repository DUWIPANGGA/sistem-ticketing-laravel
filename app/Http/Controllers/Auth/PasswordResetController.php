<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    /**
     * Display the password reset request view.
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle a password reset request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [$status],
            ]);
        }

        return back()->with('status', __($status));
    }

    /**
     * Display the password reset view.
     */
    public function edit(Request $request)
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Update the user's password.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = \Hash::make($password);
                $user->save();

                // Optionally, you can log the user in after resetting their password
                // event(new \Illuminate\Auth\Events\PasswordReset($user));
                // \Auth::login($user);
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [trans($status)],
            ]);
        }

        return redirect()->route('login')->with('status', trans(Password::PASSWORD_RESET));
    }
}
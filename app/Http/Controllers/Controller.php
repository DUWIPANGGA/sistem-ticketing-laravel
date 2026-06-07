<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    protected function requireAdmin(): void
    {
        if (Auth::user()->role !== 'admin') abort(403);
    }

    protected function requireStaff(): void
    {
        if (Auth::user()->role === 'user') abort(403);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    protected $redirectTo = '/home';

    protected function authenticated(Request $request, $user)
    {
        Log::info('User logged in', ['id' => $user->id, 'role' => $user->role, 'redirect' => $user->role === 'admin' ? '/admin/dashboard' : '/dashboard']);
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    }
}

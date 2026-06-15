<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('web')->check() && Auth::guard('web')->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::guard('web')->user();

            if (!$user->is_admin) {
                Auth::guard('web')->logout();
                return back()->withErrors(['email' => 'ئەم هەژمارە مافی چوونە ناو پانێڵی ئەدمین نییە.']);
            }

            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'ئیمەیڵ یان وشەی نهێنی هەڵەیە.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}

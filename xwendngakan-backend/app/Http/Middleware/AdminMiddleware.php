<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // پشکنینی ئایا بەکارهێنەر لۆگینکراوە و ئەدمینە
        $user = Auth::guard('web')->user() ?? Auth::guard('admin')->user();

        if (!$user || !$user->is_admin) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsStudent
{
    public function handle(Request $request, Closure $next): Response
    {
        $studentGuard = Auth::guard('student');
        $user = $studentGuard->user();

        if (! $user) {
            return redirect()->guest(route('student.login'));
        }

        if (! $user->isStudent()) {
            $studentGuard->logout();

            return redirect()
                ->route('student.login')
                ->withErrors(['email' => 'Please sign in with a student account.']);
        }

        if (! $user->is_active) {
            $studentGuard->logout();
            $request->session()->regenerateToken();

            return redirect()
                ->route('student.login')
                ->withErrors(['email' => 'Your account has been deactivated.']);
        }

        $request->setUserResolver(fn () => $user);

        return $next($request);
    }
}

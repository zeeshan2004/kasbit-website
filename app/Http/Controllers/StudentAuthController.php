<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRegisterRequest;
use App\Models\HeaderMenu;
use App\Models\HomePage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StudentAuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::guard('student')->check()) {
            return redirect()->route('feedback.index');
        }

        return view('frontend.auth.login', [
            'home' => HomePage::first() ?? new HomePage(),
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $authenticated = Auth::guard('student')->attempt([
            'email' => strtolower(trim($credentials['email'])),
            'password' => $credentials['password'],
            'role' => 'student',
            'is_active' => true,
        ], $request->boolean('remember'));

        if (! $authenticated) {
            return back()
                ->withErrors(['email' => 'The email or password is incorrect, or the account is inactive.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('feedback.index'));
    }

    public function showRegistration(): View|RedirectResponse
    {
        if (Auth::guard('student')->check()) {
            return redirect()->route('feedback.index');
        }

        return view('frontend.auth.register', [
            'home' => HomePage::first() ?? new HomePage(),
            'programGroups' => HeaderMenu::registrationProgramGroups(),
        ]);
    }

    public function register(StudentRegisterRequest $request): RedirectResponse
    {
        $user = DB::transaction(fn () => User::create([
            ...$request->safe()->except(['password_confirmation']),
            'role' => 'student',
            'is_active' => true,
        ]));

        Auth::guard('student')->login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('feedback.index')
            ->with('success', 'Your student account has been created successfully.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('student')->logout();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

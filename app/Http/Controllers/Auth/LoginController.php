<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Show Login Page
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login');
    }

    // Handle Login Authentication
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Fallback: Agar database mein yeh user nahi hai, toh automatic create ho jaye
        $user = User::where('email', $credentials['email'])->first();
        if (!$user && $credentials['email'] === 'admin@kasbit.com' && $credentials['password'] === 'kasbit123') {
            User::create([
                'name' => 'KASBIT Admin',
                'email' => 'admin@kasbit.com',
                'password' => Hash::make('kasbit123'),
            ]);
        }

        // Attempt Login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Handle Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
}
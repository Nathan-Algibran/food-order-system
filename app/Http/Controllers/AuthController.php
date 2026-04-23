<?php
/**
 * Purpose: Handle user authentication (register, login, logout)
 * Used by: Guest routes
 * Dependencies: User model, Auth facade
 * Main functions: showLogin(), login(), showRegister(), register(), logout()
 * Side effects: Creates session, creates User record
 */

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('menu.index');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nama'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'alamat'   => 'nullable|string|max:255',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'nama'     => $data['nama'],
            'email'    => $data['email'],
            'alamat'   => $data['alamat'] ?? null,
            'password' => Hash::make($data['password']),
            'role'     => 'user',
        ]);

        Auth::login($user);
        return redirect()->route('menu.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}

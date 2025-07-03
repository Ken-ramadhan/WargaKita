<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login.login'); // Sesuaikan dengan view login
    }

    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'nik' => 'required',
            'password' => 'required',
        ]);

        // Coba login pakai NIK + Password
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;

            if ($role === 'admin') {
                return redirect()->route('admin.dashboard.dashboard');
            } elseif ($role === 'rw') {
                return redirect()->route('dashboard');
            } elseif ($role === 'warga') {
                return redirect()->route('warga.dashboard.dashboard');
            }

            return redirect('/');
        }

        return back()->withErrors([
            'nik' => 'NIK atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nik' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $role = $user->role;

            // Logika filter khusus untuk warga:
            if ($role === 'warga') {
                // Ambil data warga terkait
                $warga = $user->warga;

                // Jika tidak punya relasi data warga atau bukan kepala keluarga ➜ logout lagi
                if (!$warga || strtolower($warga->status_hubungan_dalam_keluarga) !== 'kepala keluarga') {
                    Auth::logout();
                    return back()->withErrors([
                        'nik' => 'Hanya Kepala Keluarga yang bisa login.',
                    ]);
                }

                return redirect()->route('dashboard-main');
            }

            // Role lain ➜ lanjut biasa
            if ($role === 'admin') {
                return redirect()->route('dashboard-admin');
            } elseif ($role === 'rw') {
                return redirect()->route('dashboard-rw');
            } elseif ($role === 'rt') {
                return redirect()->route('dashboard-rt');
            }

            return redirect('/login');
        }

        // Jika gagal login
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

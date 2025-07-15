<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $user = User::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'password' => 'required|confirmed|min:6',
    ], [
        'current_password.required' => 'Password lama harus diisi.',
        'password.required' => 'Password baru harus diisi.',
        'password.confirmed' => 'Password baru tidak cocok.',
        'password.min' => 'Password minimal 6 karakter.',
    ]);

    $user = Auth::user();

    // Ensure $user is an instance of User model
    if (!$user instanceof \App\Models\User) {
        // Ini lebih baik ditangani di middleware atau guardian
        return back()->withErrors(['user' => 'User instance not found.'])->withInput();
    }

    // Cek apakah password lama cocok
    if (!Hash::check($request->current_password, $user->password)) {
        // Jika password lama tidak cocok, kembali dengan error dan input lama
        return back()->withErrors(['current_password' => 'Password lama tidak cocok.'])->withInput();
    }

    // Hash password baru sebelum disimpan
    $user->password = bcrypt($request->password);
    $user->save();

    // Jika berhasil, kembali dengan pesan sukses
    return back()->with('success', 'Password berhasil diperbarui!');
}

}

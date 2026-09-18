<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserProfileController extends Controller
{
    /**
     * Tampilkan Halaman Profil & Pengaturan
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.custom-show', compact('user'));
    }

    /**
     * Update Informasi Dasar (Nama & Email)
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($validated);

        return back()->with('status_profile', 'Profil berhasil diperbarui!');
    }

    /**
     * Update Password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', Password::defaults(), 'confirmed'],
        ], [
            'current_password.current_password' => 'Password saat ini tidak cocok.',
            'password.confirmed'                => 'Konfirmasi password baru tidak cocok.',
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status_password', 'Kata sandi akun Anda berhasil diganti!');
    }
}

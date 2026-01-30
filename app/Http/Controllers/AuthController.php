<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Log role info for debugging authorization issues
            Log::info('User logged in', [
                'id' => $user->id,
                'email' => $user->email,
                'role_id' => $user->role_id ?? null,
                'role_name' => optional($user->role)->name ?? null,
            ]);

            $redirect = match ($user->role?->name) {
                'Admin'         => route('dashboard'),
                'Kepala Balai'  => route('absensi.index'),
                'pegawai'       => route('absensi.index'),
                // 'Staff'         => route('staff.index'),
                default         => '/',
            };

            return redirect()->intended($redirect);
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'Email atau password salah.']);
    }



    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    
}

<?php

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

    public function processLogin(Request $request)
    {
        $credentials = $request->only('username', 'password');

        // Cari user berdasarkan username
        $user = User::where('username', $credentials['username'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['login' => 'Username atau password salah'])->withInput();
        }

        if ($user->status !== 'active') {
            return back()->withErrors(['login' => 'Akun tidak aktif'])->withInput();
        }

        Auth::login($user);

        // Arahkan berdasarkan role
        switch ($user->role) {
            case 'superadmin':
                return redirect()->route('dashboard.call_center.index');
            case 'mayor':
                return redirect()->route('dashboard.call_center.index');
            case 'deputy_mayor':
                return redirect()->route('dashboard.call_center.index');
            case 'agency':
                return redirect()->route('dashboard.call_center.index');
            default:
                Auth::logout();
                return back()->withErrors(['login' => 'Role tidak dikenali']);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}

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


    public function profile()
    {
        return view('auth.profile');
    }

    public function profileUpdate(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update user table
        $user->username = $request->username;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Update user_details table
        $user->detail()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
            ]
        );

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}

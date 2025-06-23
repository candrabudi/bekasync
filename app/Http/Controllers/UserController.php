<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereIn('role', ['superadmin', 'mayor', 'deputy_mayor'])->with('detail')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (!in_array($user->role, ['superadmin', 'mayor', 'deputy_mayor'])) {
            return redirect()->back()->with('error', 'Anda hanya dapat menghapus user dengan peran tertentu.');
        }

        if ($user->detail) {
            $user->detail->delete();
        }

        $user->delete();

        return redirect()->back()->with('success', 'Data pengguna berhasil dihapus.');
    }

    public function create()
    {
        return view('users.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username'      => 'required|string|unique:users,username',
            'password'      => 'required|string|min:6',
            'role'          => 'required|in:superadmin,mayor,deputy_mayor',
            'full_name'     => 'required|string|max:255',
            'email'         => 'nullable|email|max:255',
            'phone_number'  => 'nullable|string|max:20',
        ]);

        // Buat user utama
        $user = User::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        $user->detail()->create([
            'full_name'     => $validated['full_name'],
            'email'         => $validated['email'] ?? null,
            'phone_number'  => $validated['phone_number'] ?? null,
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::with('detail')->findOrFail($id);

        if (!in_array($user->role, ['superadmin', 'mayor', 'deputy_mayor'])) {
            abort(403, 'Tidak diizinkan mengedit user ini.');
        }

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::with('detail')->findOrFail($id);

        $validated = $request->validate([
            'username'      => 'required|string|unique:users,username,' . $user->id,
            'password'      => 'nullable|string|min:6',
            'role'          => 'required|in:superadmin,mayor,deputy_mayor',
            'full_name'     => 'required|string|max:255',
            'email'         => 'nullable|email|max:255',
            'phone_number'  => 'nullable|string|max:20',
        ]);

        $user->username = $validated['username'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $user->detail()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'full_name'     => $validated['full_name'],
                'email'         => $validated['email'] ?? null,
                'phone_number'  => $validated['phone_number'] ?? null,
            ]
        );

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }
}

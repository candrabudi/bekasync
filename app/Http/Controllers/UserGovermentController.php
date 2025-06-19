<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetail;
use App\Models\GovernmentUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserGovermentController extends Controller
{
    public function index()
    {
        $agencies = User::with('detail.governmentUnit')
            ->where('role', 'agency')
            ->latest()
            ->paginate(10); // misalnya tampil 10 data per halaman

        return view('agencies.index', compact('agencies'));
    }

    public function create()
    {
        $units = GovernmentUnit::all();
        return view('agencies.create', compact('units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username'           => 'required|unique:users',
            'password'           => 'required|min:6',
            'full_name'          => 'required|string|max:255',
            'email'              => 'nullable|email',
            'phone_number'       => 'nullable|string',
            'government_unit_id' => 'nullable|exists:government_units,id',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role'     => 'agency',
        ]);

        $user->detail()->create([
            'full_name'          => $validated['full_name'],
            'email'              => $validated['email'] ?? null,
            'phone_number'       => $validated['phone_number'] ?? null,
            'government_unit_id' => $validated['government_unit_id'] ?? null,
        ]);

        return redirect()
            ->route('agencies.index')
            ->with('success', 'Agency created.');
    }

    public function edit($a)
    {
        $agency = User::where('id', $a)
            ->first();
        abort_if($agency->role !== 'superadmin', 403);

        $units  = GovernmentUnit::all();
        $detail = $agency->detail;

        return view('agencies.edit', compact('agency', 'detail', 'units'));
    }

    public function update(Request $request, User $agency)
    {
        abort_if($agency->role !== 'superadmin', 403);

        $validated = $request->validate([
            'username'           => ['required', Rule::unique('users')->ignore($agency->id)],
            'password'           => 'nullable|min:6',
            'full_name'          => 'required|string|max:255',
            'email'              => 'nullable|email',
            'phone_number'       => 'nullable|string',
            'government_unit_id' => 'nullable|exists:government_units,id',
        ]);

        $agency->update([
            'username' => $validated['username'],
            'password' => $validated['password']
                ? Hash::make($validated['password'])
                : $agency->password,
        ]);

        $agency->detail()->updateOrCreate(
            ['user_id' => $agency->id],
            [
                'full_name'          => $validated['full_name'],
                'email'              => $validated['email'] ?? null,
                'phone_number'       => $validated['phone_number'] ?? null,
                'government_unit_id' => $validated['government_unit_id'] ?? null,
            ]
        );

        return redirect()
            ->route('agencies.index')
            ->with('success', 'Agency updated.');
    }

    public function destroy(User $agency)
    {
        abort_if($agency->role !== 'superadmin', 403);

        $agency->delete();

        return redirect()
            ->route('agencies.index')
            ->with('success', 'Agency deleted.');
    }
}

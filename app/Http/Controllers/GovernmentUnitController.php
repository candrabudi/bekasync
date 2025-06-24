<?php

namespace App\Http\Controllers;

use App\Models\GovernmentUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GovernmentUnitController extends Controller
{
    public function index()
    {

        return view('government_units.index');
    }

    public function data(Request $request)
    {
        $query = GovernmentUnit::with(['userDetails.user'])
            ->withCount(['userDetails']);

        // Filter pencarian jika ada
        if ($request->has('search') && $request->search !== '') {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('long_name', 'like', '%' . $request->search . '%');
            });
        }

        $units = $query->paginate(10);

        $units->getCollection()->transform(function ($unit) {
            $active = 0;
            $inactive = 0;

            foreach ($unit->userDetails as $detail) {
                if ($detail->user && $detail->user->status === 'active') {
                    $active++;
                }
                if ($detail->user && $detail->user->status === 'inactive') {
                    $inactive++;
                }
            }

            $unit->active_users = $active;
            $unit->inactive_users = $inactive;

            unset($unit->userDetails); // optional
            return $unit;
        });

        return response()->json($units);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:government_units,name',
            'long_name' => 'required|unique:government_units,long_name',
        ]);

        $unit = GovernmentUnit::create($request->only('name', 'long_name'));
        return response()->json($unit);
    }

    public function update(Request $request, GovernmentUnit $governmentUnit)
    {
        $request->validate([
            'name' => 'required|unique:government_units,name,' . $governmentUnit->id,
            'long_name' => 'required|unique:government_units,long_name,' . $governmentUnit->id,
        ]);

        $governmentUnit->update($request->only('name', 'long_name'));
        return response()->json($governmentUnit);
    }

    public function destroy(GovernmentUnit $governmentUnit)
    {
        $governmentUnit->delete();
        return response()->json(['success' => true]);
    }
}

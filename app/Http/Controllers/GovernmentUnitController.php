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
        $units = GovernmentUnit::with(['userDetails.user'])
            ->withCount(['userDetails'])
            ->paginate(10); // limit 10 per page

        // Tambahkan count aktif / non aktif
        $units->getCollection()->transform(function ($unit) {
            $active = 0;
            $inactive = 0;

            foreach ($unit->userDetails as $detail) {
                if ($detail->user && $detail->user->status === 'active')
                    $active++;
                if ($detail->user && $detail->user->status === 'inactive')
                    $inactive++;
            }

            $unit->active_users = $active;
            $unit->inactive_users = $inactive;

            unset($unit->userDetails); // optional supaya response tidak terlalu besar
            return $unit;
        });

        return response()->json($units);
    }
}

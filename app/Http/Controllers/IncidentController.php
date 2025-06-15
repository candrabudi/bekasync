<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncidentReport;
use App\Models\AgencyResponse;
use App\Models\IncidentLog;

class IncidentController extends Controller
{
    /**
     * Halaman utama list incident
     */
    public function index(Request $request)
    {
        return view('incidents.index', [
            'categories' => IncidentReport::distinct()->pluck('category')->filter()->sort()->values(),
            'districts' => IncidentReport::distinct()->pluck('district')->filter()->sort()->values(),
            'subdistricts' => IncidentReport::distinct()->pluck('subdistrict')->filter()->sort()->values(),
        ]);
    }

    /**
     * Fetch data via AJAX (dengan filter & pagination)
     */
    public function fetch(Request $request)
    {
        $query = IncidentReport::query();

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('ticket', 'like', "%{$request->search}%")
                    ->orWhere('caller', 'like', "%{$request->search}%")
                    ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        // Filters
        if ($request->start_date) {
            $query->whereDate('incident_created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('incident_created_at', '<=', $request->end_date);
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->district) {
            $query->where('district', $request->district);
        }

        if ($request->subdistrict) {
            $query->where('subdistrict', $request->subdistrict);
        }

        return response()->json(
            $query->orderByDesc('incident_created_at')->paginate(9)
        );
    }

    /**
     * Tampilkan detail 1 incident report
     */
    public function show($id)
    {
        $incident = IncidentReport::findOrFail($id);

        // Ambil respons dari dinas terkait
        $agencyResponses = AgencyResponse::where('incident_report_id', $id)->get();

        // Ambil log perubahan status
        $logs = IncidentLog::where('incident_report_id', $id)
            ->orderByDesc('created_at')
            ->get();

        return view('incidents.show', compact('incident', 'agencyResponses', 'logs'));
    }
}

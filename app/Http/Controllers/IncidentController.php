<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncidentReport;
use App\Models\AgencyResponse;
use App\Models\IncidentLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IncidentController extends Controller
{
    public function index(Request $request)
    {
        return view('incidents.index', [
            'categories' => IncidentReport::distinct()->pluck('category')->filter()->sort()->values(),
            'districts' => IncidentReport::distinct()->pluck('district')->filter()->sort()->values(),
            'subdistricts' => IncidentReport::distinct()->pluck('subdistrict')->filter()->sort()->values(),
        ]);
    }

    public function fetch(Request $request)
    {
        $user = Auth::user();
        $query = IncidentReport::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('ticket', 'like', "%{$request->search}%")
                    ->orWhere('caller', 'like', "%{$request->search}%")
                    ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

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

        if ($user->role === 'agency') {
            $query->join('agency_responses', 'agency_responses.incident_report_id', '=', 'incident_reports.id')
                ->where('agency_responses.dinas', $user->detail->governmentUnit->name);
        }

        return response()->json(
            $query->orderBy('incident_reports.created_at', 'desc')->paginate(9)
        );
    }

    public function show($id)
    {
        $incident = IncidentReport::findOrFail($id);

        $agencyResponses = AgencyResponse::where('incident_report_id', $id)->get();

        $logs = IncidentLog::where('incident_report_id', $id)
            ->orderByDesc('created_at')
            ->get();

        return view('incidents.show', compact('incident', 'agencyResponses', 'logs'));
    }

    public function showByDinas($a)
    {
        $agency = strtoupper($a);
        $categories = DB::table('incident_reports')->distinct()->pluck('category')->filter();
        $districts = DB::table('incident_reports')->distinct()->pluck('district')->filter();
        $subdistricts = DB::table('incident_reports')->distinct()->pluck('subdistrict')->filter();

        return view('incidents.incident_opd', compact('categories', 'districts', 'subdistricts', 'agency'));
    }

    public function showByCategory($a)
    {
        $category = strtoupper($a);
        $categories = DB::table('incident_reports')->distinct()->pluck('category')->filter();
        $districts = DB::table('incident_reports')->distinct()->pluck('district')->filter();
        $subdistricts = DB::table('incident_reports')->distinct()->pluck('subdistrict')->filter();

        return view('incidents.incident_category', compact('categories', 'districts', 'subdistricts', 'category'));
    }

    public function showByStatus($status)
    {
        $categories = DB::table('incident_reports')->distinct()->pluck('category')->filter();
        $districts = DB::table('incident_reports')->distinct()->pluck('district')->filter();
        $subdistricts = DB::table('incident_reports')->distinct()->pluck('subdistrict')->filter();

        return view('incidents.incident_status', compact('categories', 'districts', 'subdistricts', 'status'));
    }

    public function showByDistrict($district)
    {
        $categories = DB::table('incident_reports')->distinct()->pluck('category')->filter();
        $districts = DB::table('incident_reports')->distinct()->pluck('district')->filter();
        $subdistricts = DB::table('incident_reports')->distinct()->pluck('subdistrict')->filter();

        return view('incidents.incident_district', compact('categories', 'districts', 'subdistricts', 'district'));
    }

    public function getReportsByDinas(Request $request, $a)
    {
        $query = DB::table('incident_reports')
            ->join('agency_responses', 'incident_reports.id', '=', 'agency_responses.incident_report_id')
            ->where('agency_responses.dinas', $a)
            ->select(
                'incident_reports.id',
                'incident_reports.ticket',
                'incident_reports.category',
                'incident_reports.status',
                'incident_reports.phone',
                'incident_reports.caller',
                'incident_reports.location',
                'incident_reports.district',
                'incident_reports.subdistrict',
                'incident_reports.call_type',
                'incident_reports.channel_id',
                'incident_reports.created_at as incident_created_at',
                'agency_responses.dinas as opd_name'
            );

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('incident_reports.ticket', 'like', "%$search%")
                    ->orWhere('incident_reports.phone', 'like', "%$search%")
                    ->orWhere('incident_reports.caller', 'like', "%$search%");
            });
        }

        if ($request->filled('category')) {
            $query->where('incident_reports.category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('incident_reports.status', $request->status);
        }

        if ($request->filled('district')) {
            $query->where('incident_reports.district', $request->district);
        }

        if ($request->filled('subdistrict')) {
            $query->where('incident_reports.subdistrict', $request->subdistrict);
        }

        if ($request->filled('call_type')) {
            $query->where('incident_reports.call_type', $request->call_type);
        }

        if ($request->filled('channel_id')) {
            $query->where('incident_reports.channel_id', $request->channel_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('incident_reports.created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('incident_reports.created_at', '<=', $request->end_date);
        }

        $reports = $query->orderBy('incident_reports.created_at', 'desc')->paginate(9);

        return response()->json($reports);
    }


    public function getReportsByCategory(Request $request, $a)
    {
        $user = Auth::user();
        $query = DB::table('incident_reports')
            ->join('agency_responses', 'incident_reports.id', '=', 'agency_responses.incident_report_id')
            ->where('incident_reports.category', $a)
            ->select(
                'incident_reports.id',
                'incident_reports.ticket',
                'incident_reports.category',
                'incident_reports.status',
                'incident_reports.phone',
                'incident_reports.caller',
                'incident_reports.location',
                'incident_reports.district',
                'incident_reports.subdistrict',
                'incident_reports.call_type',
                'incident_reports.channel_id',
                'incident_reports.created_at as incident_created_at',
                'agency_responses.dinas as opd_name'
            );

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('incident_reports.ticket', 'like', "%$search%")
                    ->orWhere('incident_reports.phone', 'like', "%$search%")
                    ->orWhere('incident_reports.caller', 'like', "%$search%");
            });
        }

        if ($user->role === 'agency') {
            $query->join('agency_responses as ar', 'ar.incident_report_id', '=', 'incident_reports.id')
                ->where('ar.dinas', $user->detail->governmentUnit->name);
        }

        if ($request->filled('category')) {
            $query->where('incident_reports.category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('incident_reports.status', $request->status);
        }

        if ($request->filled('district')) {
            $query->where('incident_reports.district', $request->district);
        }

        if ($request->filled('subdistrict')) {
            $query->where('incident_reports.subdistrict', $request->subdistrict);
        }

        if ($request->filled('call_type')) {
            $query->where('incident_reports.call_type', $request->call_type);
        }

        if ($request->filled('channel_id')) {
            $query->where('incident_reports.channel_id', $request->channel_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('incident_reports.created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('incident_reports.created_at', '<=', $request->end_date);
        }

        $reports = $query->orderBy('incident_reports.created_at', 'desc')->paginate(9);

        return response()->json($reports);
    }

    public function getReportsByStatus(Request $request, $status)
    {
        $user = Auth::user();
        $query = DB::table('incident_reports')
            ->join('agency_responses', 'incident_reports.id', '=', 'agency_responses.incident_report_id')
            ->where('incident_reports.status', $status)
            ->select(
                'incident_reports.id',
                'incident_reports.ticket',
                'incident_reports.category',
                'incident_reports.status',
                'incident_reports.phone',
                'incident_reports.caller',
                'incident_reports.location',
                'incident_reports.district',
                'incident_reports.subdistrict',
                'incident_reports.call_type',
                'incident_reports.channel_id',
                'incident_reports.created_at as incident_created_at',
                'agency_responses.dinas as opd_name'
            );

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('incident_reports.ticket', 'like', "%$search%")
                    ->orWhere('incident_reports.phone', 'like', "%$search%")
                    ->orWhere('incident_reports.caller', 'like', "%$search%");
            });
        }

        if ($user->role === 'agency') {
            $query->join('agency_responses as ar', 'ar.incident_report_id', '=', 'incident_reports.id')
                ->where('ar.dinas', $user->detail->governmentUnit->name);
        }

        if ($request->filled('category')) {
            $query->where('incident_reports.category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('incident_reports.status', $request->status);
        }

        if ($request->filled('district')) {
            $query->where('incident_reports.district', $request->district);
        }

        if ($request->filled('subdistrict')) {
            $query->where('incident_reports.subdistrict', $request->subdistrict);
        }

        if ($request->filled('call_type')) {
            $query->where('incident_reports.call_type', $request->call_type);
        }

        if ($request->filled('channel_id')) {
            $query->where('incident_reports.channel_id', $request->channel_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('incident_reports.created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('incident_reports.created_at', '<=', $request->end_date);
        }

        $reports = $query->orderBy('incident_reports.created_at', 'desc')->paginate(9);

        return response()->json($reports);
    }


    public function getReportsByDistrict(Request $request, $district)
    {
        $user = Auth::user();
        $query = DB::table('incident_reports')
            ->join('agency_responses', 'incident_reports.id', '=', 'agency_responses.incident_report_id')
            ->where('incident_reports.district', $district)
            ->select(
                'incident_reports.id',
                'incident_reports.ticket',
                'incident_reports.category',
                'incident_reports.status',
                'incident_reports.phone',
                'incident_reports.caller',
                'incident_reports.location',
                'incident_reports.district',
                'incident_reports.subdistrict',
                'incident_reports.call_type',
                'incident_reports.channel_id',
                'incident_reports.created_at as incident_created_at',
                'agency_responses.dinas as opd_name'
            );

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('incident_reports.ticket', 'like', "%$search%")
                    ->orWhere('incident_reports.phone', 'like', "%$search%")
                    ->orWhere('incident_reports.caller', 'like', "%$search%");
            });
        }

        if ($user->role === 'agency') {
            $query->join('agency_responses as ar', 'ar.incident_report_id', '=', 'incident_reports.id')
                ->where('ar.dinas', $user->detail->governmentUnit->name);
        }

        if ($request->filled('category')) {
            $query->where('incident_reports.category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('incident_reports.status', $request->status);
        }

        if ($request->filled('district')) {
            $query->where('incident_reports.district', $request->district);
        }

        if ($request->filled('subdistrict')) {
            $query->where('incident_reports.subdistrict', $request->subdistrict);
        }

        if ($request->filled('call_type')) {
            $query->where('incident_reports.call_type', $request->call_type);
        }

        if ($request->filled('channel_id')) {
            $query->where('incident_reports.channel_id', $request->channel_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('incident_reports.created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('incident_reports.created_at', '<=', $request->end_date);
        }

        $reports = $query->orderBy('incident_reports.created_at', 'desc')->paginate(9);

        return response()->json($reports);
    }
}

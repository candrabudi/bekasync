<?php

namespace App\Http\Controllers;

use App\Models\IncidentReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DashboardCallCenterController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'agency') {
            return view('dashboards.call-center.opd');
        } else {
            return view('dashboards.call-center.index');
        }
    }

    public function getDispatcher(Request $request)
    {
        $loginResponse = Http::accept('application/json')->post('https://kotabekasiv2.sakti112.id/api/services/login', [
            'username' => 'apikotabekasi@sakti112.id',
            'password' => '_cH_h8_cLnGYBQH',
        ]);

        if (!$loginResponse->successful()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal login ke API eksternal'
            ], 500);
        }

        $loginData = $loginResponse->json();

        if (!isset($loginData['status']) || !$loginData['status']) {
            return response()->json([
                'status' => false,
                'message' => 'Login ditolak oleh API eksternal'
            ], 401);
        }

        $token = $loginData['content']['access_token'] ?? null;
        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Token akses tidak ditemukan'
            ], 500);
        }

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->get('https://kotabekasiv2.sakti112.id/api/v3/dispatcher/get-dispatcher');

        if (!$response->successful()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mendapatkan data dispatcher'
            ], 500);
        }

        $data = $response->json();

        return response()->json($data);
    }

    public function dataCardIncident(Request $request)
    {
        $user = Auth::user();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = IncidentReport::whereNotNull('category')->where('category', '!=', '-');

        if ($startDate && $endDate) {
            $query->whereDate('incident_reports.created_at', '>=', $startDate)
                ->whereDate('incident_reports.created_at', '<=', $endDate);
        } else {
            $today = Carbon::today();
            $query->whereDate('incident_reports.created_at', $today);
        }

        if ($user->role === 'agency') {
            $query->join('agency_responses', 'agency_responses.incident_report_id', '=', 'incident_reports.id')
                ->where('agency_responses.dinas', $user->detail->governmentUnit->name);
        }

        $statusBaru = (clone $query)->where('incident_reports.status', 1)->count();
        $statusDiproses = (clone $query)->where('incident_reports.status', 2)->count();
        $statusSelesai = (clone $query)->where('incident_reports.status', 3)->count();
        $total = (clone $query)->count();

        return response()->json([
            'status_baru' => $statusBaru,
            'status_diproses' => $statusDiproses,
            'status_selesai' => $statusSelesai,
            'total' => $total,
        ]);
    }

    public function getDailyReportChartDataPerHour(Request $request)
    {
        $user = Auth::user();
        $startDate = $request->input('start_date') ?? Carbon::today()->toDateString();
        $endDate = $request->input('end_date') ?? Carbon::today()->toDateString();

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $hours = range(0, 23);
        $labels = array_map(fn($h) => sprintf('%02d:00', $h), $hours);

        $countPerStatusPerHour = function ($status) use ($start, $end, $hours, $user) {
            $counts = [];

            foreach ($hours as $hour) {
                $startHour = (clone $start)->setTime($hour, 0, 0);
                $endHour = (clone $start)->setTime($hour, 59, 59);

                $query = IncidentReport::where('status', $status)
                    ->whereDate('created_at', '>=', $start)
                    ->whereDate('created_at', '<=', $end)
                    ->whereTime('created_at', '>=', $startHour->toTimeString())
                    ->whereTime('created_at', '<=', $endHour->toTimeString())
                    ->whereNotNull('category')
                    ->where('category', '!=', '-');

                if ($user->role === 'agency') {
                    $query->whereHas('agencyResponses', function ($q) use ($user) {
                        $q->where('dinas', $user->detail->governmentUnit->name);
                    });
                }

                $counts[] = $query->count();
            }

            return $counts;
        };

        $dataBaru = $countPerStatusPerHour(1);
        $dataDiproses = $countPerStatusPerHour(2);
        $dataSelesai = $countPerStatusPerHour(3);

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Baru',
                    'data' => $dataBaru,
                    'borderColor' => '#1a73e8',
                    'backgroundColor' => 'transparent',
                ],
                [
                    'label' => 'Diproses',
                    'data' => $dataDiproses,
                    'borderColor' => '#fbbc04',
                    'backgroundColor' => 'transparent',
                ],
                [
                    'label' => 'Selesai',
                    'data' => $dataSelesai,
                    'borderColor' => '#34a853',
                    'backgroundColor' => 'transparent',
                ],
            ],
        ]);
    }

    public function getDailyReportChartDataPerDay(Request $request)
    {
        $user = Auth::user();
        $period = $request->input('period', 'last7days');

        $today = Carbon::today();

        if ($period === 'last7days') {
            $startDate = $today->copy()->subDays(6)->toDateString();
            $endDate = $today->toDateString();
        } elseif ($period === 'thismonth') {
            $startDate = $today->copy()->startOfMonth()->toDateString();
            $endDate = $today->toDateString();
        } else {
            $startDate = $today->copy()->subDays(6)->toDateString();
            $endDate = $today->toDateString();
        }

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $dateRange = [];
        $periodDate = $start->copy();
        while ($periodDate->lte($end)) {
            $dateRange[] = $periodDate->toDateString();
            $periodDate->addDay();
        }

        $query = IncidentReport::selectRaw("DATE(created_at) as date, COUNT(*) as total")
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->whereNotNull('category')
            ->where('category', '!=', '-');

        if ($user->role === 'agency') {
            $query->whereHas('agencyResponses', function ($q) use ($user) {
                $q->where('dinas_id', $user->agency_id);
            });
        }

        $rawData = $query->groupByRaw("DATE(created_at)")
            ->pluck('total', 'date');

        $labels = [];
        $values = [];

        foreach ($dateRange as $date) {
            $labels[] = Carbon::parse($date)->format('d/m');
            $values[] = $rawData[$date] ?? 0;
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Laporan',
                    'data' => $values,
                    'borderColor' => '#1a73e8',
                    'backgroundColor' => 'rgba(26, 115, 232, 0.15)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                    'pointBackgroundColor' => '#1a73e8',
                    'pointBorderColor' => '#fff',
                    'borderWidth' => 3,
                    'hoverBorderWidth' => 4,
                ]
            ]
        ]);
    }

    public function getTop5IncidentByCategories(Request $request)
    {
        $user = Auth::user();
        $startDate = $request->input('start_date') ?? Carbon::today()->toDateString();
        $endDate = $request->input('end_date') ?? Carbon::today()->toDateString();

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $query = DB::table('incident_reports')
            ->select(
                'category',
                DB::raw("SUM(CASE WHEN incident_reports.status IN (2, 3) THEN 1 ELSE 0 END) as total"),
                DB::raw("SUM(CASE WHEN incident_reports.status = '3' THEN 1 ELSE 0 END) as selesai_count"),
                DB::raw("SUM(CASE WHEN incident_reports.status = '2' THEN 1 ELSE 0 END) as proses_count")
            )
            ->whereNotNull('category')
            ->where('category', '!=', '-')
            ->whereDate('incident_reports.created_at', '>=', $start)
            ->whereDate('incident_reports.created_at', '<=', $end);

        if ($user->role === 'agency') {
            $query->join('agency_responses', 'agency_responses.incident_report_id', '=', 'incident_reports.id')
                ->where('agency_responses.dinas', $user->detail->governmentUnit->name);
        }

        $data = $query->groupBy('category')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return response()->json($data);
    }

    public function getTop5IncidentByDistrict(Request $request)
    {
        $user = Auth::user();
        $startDate = $request->input('start_date') ?? Carbon::today()->toDateString();
        $endDate = $request->input('end_date') ?? Carbon::today()->toDateString();

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $query = DB::table('incident_reports')
            ->select(
                'district',
                DB::raw("SUM(CASE WHEN incident_reports.status IN (2, 3) THEN 1 ELSE 0 END) as total"),
                DB::raw("SUM(CASE WHEN incident_reports.status = '2' THEN 1 ELSE 0 END) as selesai_count"),
                DB::raw("SUM(CASE WHEN incident_reports.status = '3' THEN 1 ELSE 0 END) as proses_count")
            )
            ->whereNotNull('district')
            ->where('district', '!=', '-')
            ->whereDate('incident_reports.created_at', '>=', $start)
            ->whereDate('incident_reports.created_at', '<=', $end);

        if ($user->role === 'agency') {
            $query->join('agency_responses', 'agency_responses.incident_report_id', '=', 'incident_reports.id')
                ->where('agency_responses.dinas', $user->detail->governmentUnit->name);
        }

        $data = $query->groupBy('district')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return response()->json($data);
    }
    public function getTop5IncidentByDinas(Request $request)
    {
        $startDate = $request->input('start_date') ?? Carbon::today()->toDateString();
        $endDate = $request->input('end_date') ?? Carbon::today()->toDateString();

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $data = DB::table('agency_responses')
            ->join('incident_reports', 'agency_responses.incident_report_id', '=', 'incident_reports.id')
            ->select(
                'agency_responses.dinas',
                DB::raw("SUM(CASE WHEN agency_responses.status IN (2, 3) THEN 1 ELSE 0 END) as total"),
                DB::raw("SUM(CASE WHEN agency_responses.status = 2 THEN 1 ELSE 0 END) as selesai_count"),
                DB::raw("SUM(CASE WHEN agency_responses.status = 3 THEN 1 ELSE 0 END) as proses_count")
            )
            ->whereNotNull('agency_responses.dinas')
            ->where('agency_responses.dinas', '!=', '-')
            ->whereDate('incident_reports.created_at', '>=', $start)
            ->whereDate('incident_reports.created_at', '<=', $end)
            ->groupBy('agency_responses.dinas')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return response()->json($data);
    }

    public function getTop5MostResponsiveDinas(Request $request)
    {
        $startDate = $request->input('start_date') ?? Carbon::today()->toDateString();
        $endDate = $request->input('end_date') ?? Carbon::today()->toDateString();

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $firstResponseSubquery = DB::table('agency_responses as ar1')
            ->select('ar1.incident_report_id', 'ar1.dinas', DB::raw('MIN(ar1.created_at) as first_response_at'))
            ->groupBy('ar1.incident_report_id', 'ar1.dinas');

        $data = DB::table(DB::raw("({$firstResponseSubquery->toSql()}) as first_responses"))
            ->mergeBindings($firstResponseSubquery)
            ->join('incident_reports as ir', 'first_responses.incident_report_id', '=', 'ir.id')
            ->select(
                'first_responses.dinas',
                DB::raw('COUNT(*) as total_responses'),
                DB::raw('AVG(TIMESTAMPDIFF(SECOND, ir.created_at, first_responses.first_response_at)) as avg_response_time_seconds')
            )
            ->whereNotNull('first_responses.dinas')
            ->where('first_responses.dinas', '!=', '-')
            ->whereBetween('ir.created_at', [$start, $end])
            ->groupBy('first_responses.dinas')
            ->orderBy('avg_response_time_seconds', 'asc')
            ->limit(5)
            ->get();

        return response()->json($data);
    }


    public function hourlyStats(Request $request)
    {
        $date = $request->input('date') ?? Carbon::today()->toDateString();

        $records = DB::table('cdr_reports')
            ->select(
                DB::raw('HOUR(datetime_entry_queue) as hour'),
                DB::raw("SUM(CASE WHEN status = 'abandonada' THEN 1 ELSE 0 END) as abandoned"),
                DB::raw("SUM(CASE WHEN status = 'activa' THEN 1 ELSE 0 END) as active"),
                DB::raw("SUM(CASE WHEN status = 'en-cola' THEN 1 ELSE 0 END) as en_cola"),
                DB::raw("SUM(CASE WHEN status = 'completada' THEN 1 ELSE 0 END) as completed")
            )
            ->whereDate('datetime_entry_queue', $date)
            ->groupBy(DB::raw('HOUR(datetime_entry_queue)'))
            ->get();

        $hourlyData = collect(range(0, 23))->map(function ($hour) use ($records) {
            $record = $records->firstWhere('hour', $hour);
            return [
                'hour' => str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00',
                'abandoned' => $record->abandoned ?? 0,
                'active' => $record->active ?? 0,
                'en_cola' => $record->en_cola ?? 0,
                'completed' => $record->completed ?? 0,
            ];
        });

        return response()->json($hourlyData);
    }


    public function getSpeedmeterData(Request $request)
    {
        try {
            $loginResponse = Http::withHeaders([
                'accept' => 'application/json',
            ])->post('https://kotabekasiv2.sakti112.id/api/services/login', [
                'username' => 'apikotabekasi@sakti112.id',
                'password' => '_cH_h8_cLnGYBQH'
            ]);

            if (!$loginResponse->successful()) {
                return response()->json(['error' => 'Login gagal'], 500);
            }

            $token = $loginResponse->json('content.access_token');

            $startDate = $request->input('start_date', Carbon::now()->format('Y-m-d'));
            $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

            $dataResponse = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ])->post('https://kotabekasiv2.sakti112.id/api/v2/wallboard/112/ticket/today-incidents', [
                'date' => [$startDate, $endDate]
            ]);

            return $dataResponse->json();

            if (!$dataResponse->successful()) {
                return response()->json(['error' => 'Gagal mengambil data incident'], 500);
            }

            return response()->json($dataResponse->json());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function getTotalTypeReport(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;
        $response = Http::get('https://kotabekasiv2.sakti112.id/master/wallboard/get-summary-insiden', [
            'date' => [Carbon::parse($start)->format('d/m/Y'), Carbon::parse($end)->format('d/m/Y')]
        ]);

        return response()->json($response->json());
    }

    public function wallBoardGetSummaryCall(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;
        $response = Http::get('https://kotabekasiv2.sakti112.id/master/wallboard/get-summary-call', [
            'date' => [Carbon::parse($start)->format('d/m/Y'), Carbon::parse($end)->format('d/m/Y')]
        ]);

        return response()->json($response->json());
    }
}

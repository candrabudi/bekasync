<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\IncidentReport;
use App\Models\AgencyResponse;
use App\Models\IncidentLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class IncidentSyncController extends Controller
{
    public function sync()
    {
        set_time_limit(1000);

        $loginResponse = Http::withHeaders([
            'accept' => 'application/json',
            'X-CSRF-TOKEN' => ''
        ])->post('https://kotabekasiv2.sakti112.id/api/services/login', [
            'username' => 'apikotabekasi@sakti112.id',
            'password' => '_cH_h8_cLnGYBQH',
        ]);

        if (!$loginResponse->ok() || !isset($loginResponse['content']['access_token'])) {
            return response()->json(['message' => 'Gagal mengambil access token'], 401);
        }

        $accessToken = $loginResponse['content']['access_token'];

        // Ambil hanya data kemarin dan hari ini
        $startDate = Carbon::yesterday()->startOfDay();
        $endDate = Carbon::today()->endOfDay();
        $currentDate = $startDate;
        $totalSynced = 0;

        while ($currentDate->lte($endDate)) {
            $dateString = $currentDate->format('Y-m-d');

            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $accessToken
                ])->post('https://kotabekasiv2.sakti112.id/api/v3/layer1/laporan-insiden', [
                    'date' => $dateString
                ]);

            if (!$response->ok()) {
                Log::error("Sync gagal untuk tanggal {$dateString}");
                $currentDate->addDay();
                continue;
            }

            $data = $response->json();

            foreach ($data as $item) {
                $incident = IncidentReport::updateOrCreate(
                    ['ticket' => $item['ticket']],
                    [
                        'channel_id' => $item['channel_id'] ?? null,
                        'category' => $item['category'] ?? null,
                        'category_id' => $item['category_id'] ?? null,
                        'status' => $item['status'] ?? null,
                        'call_type' => $item['call_type'] ?? null,
                        'caller_id' => $item['caller_id'] ?? null,
                        'phone' => $item['phone'] ?? null,
                        'phone_unmask' => $item['phone_unmask'] ?? null,
                        'voip_number' => $item['voip_number'] ?? null,
                        'caller' => $item['caller'] ?? null,
                        'created_by' => $item['created_by'] ?? null,
                        'address' => $item['address'] ?? null,
                        'location' => $item['location'] ?? null,
                        'district_id' => $item['district_id'] ?? null,
                        'district' => $item['district'] ?? null,
                        'subdistrict_id' => $item['subdistrict_id'] ?? null,
                        'subdistrict' => $item['subdistrict'] ?? null,
                        'notes' => $item['notes'] ?? null,
                        'description' => $item['description'] ?? null,
                        'created_at' => $this->normalizeDatetime($item['created_at'] ?? null),
                        'updated_at' => $this->normalizeDatetime($item['updated_at'] ?? null),
                    ]
                );

                $incidentId = $incident->id;

                if (!empty($item['dinas_terkait'])) {
                    foreach ($item['dinas_terkait'] as $agency) {
                        $exists = AgencyResponse::where('ticket', $item['ticket'])
                            ->where('report_id', $agency['report_id'])
                            ->where('dinas_id', $agency['dinas_id'] ?? null)
                            ->first();

                        if (!$exists) {
                            AgencyResponse::create([
                                'incident_report_id' => $incidentId,
                                'report_id' => $agency['report_id'],
                                'ticket' => $item['ticket'],
                                'dinas_id' => $agency['dinas_id'] ?? null,
                                'dinas' => $agency['dinas'] ?? null,
                                'status' => $agency['status'] ?? null,
                                'created_at' => $this->normalizeDatetime($agency['created_at'] ?? null),
                                'updated_at' => $this->normalizeDatetime($agency['updated_at'] ?? null),
                            ]);
                        }
                    }
                }

                if (!empty($item['log_ticket'])) {
                    foreach ($item['log_ticket'] as $log) {
                        $exists = IncidentLog::where('ticket', $item['ticket'])
                            ->where('status', $log['status'] ?? null)
                            ->where('created_at', $this->normalizeDatetime($log['created_at'] ?? null))
                            ->first();

                        if (!$exists) {
                            IncidentLog::create([
                                'incident_report_id' => $incidentId,
                                'ticket' => $item['ticket'],
                                'status' => $log['status'] ?? null,
                                'created_by' => $log['created_by'] ?? null,
                                'created_by_name' => $log['created_by_name'] ?? null,
                                'updated_by' => $log['updated_by'] ?? null,
                                'updated_by_name' => $log['updated_by_name'] ?? null,
                                'created_at' => $this->normalizeDatetime($log['created_at'] ?? null),
                                'updated_at' => $this->normalizeDatetime($log['updated_at'] ?? null),
                            ]);
                        }
                    }
                }
            }

            $totalSynced += count($data);
            $currentDate->addDay();
        }

        return response()->json(['message' => 'Sync completed', 'total_synced' => $totalSynced]);
    }

    private function normalizeDatetime($value)
    {
        try {
            // Try parsing and converting to UTC
            return Carbon::parse($value)->setTimezone('UTC')->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            Log::warning("Invalid datetime format or DST gap: {$value}. Using now() instead.");
            return now()->setTimezone('UTC')->format('Y-m-d H:i:s');
        }
    }




}

<?php

namespace App\Http\Controllers;

use App\Models\CdrReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CdrReportSyncController extends Controller
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

        $token = $loginResponse['content']['access_token'];
        $startDate = Carbon::yesterday(); // Kemarin
        $endDate = Carbon::today();       // Hari ini

        $url = 'https://kotabekasiv2.sakti112.id/api/v3/layer1/laporan-cdr';
        $totalImported = 0;

        while ($startDate->lte($endDate)) {
            $formattedDate = $startDate->format('Y-m-d');
            logger("Fetching CDR for $formattedDate");

            try {
                $response = Http::withToken($token)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post($url, ['date' => $formattedDate]);

                if ($response->successful()) {
                    $cdrs = $response->json();

                    foreach ($cdrs as $cdr) {
                        CdrReport::updateOrCreate(
                            ['uniqueid' => $cdr['uniqueid']],
                            [
                                'ticket' => $cdr['ticket'],
                                'phone' => $cdr['phone'],
                                'phone_unmask' => $cdr['phone_unmask'],
                                'voip_number' => $cdr['voip_number'],
                                'datetime_entry_queue' => $cdr['datetime_entry_queue'],
                                'duration_wait' => $cdr['duration_wait'],
                                'datetime_init' => $cdr['datetime_init'],
                                'datetime_end' => $cdr['datetime_end'],
                                'duration' => $cdr['duration'],
                                'status' => $cdr['status'],
                                'extension' => $cdr['extension'],
                                'agent_name' => $cdr['nama_agen'],
                                'recording_file' => $cdr['recording_file'],
                            ]
                        );
                    }

                    $totalImported += count($cdrs);
                } else {
                    logger("Failed to fetch CDR for $formattedDate. Status: " . $response->status());
                }
            } catch (\Exception $e) {
                logger("Error on $formattedDate: " . $e->getMessage());
            }

            $startDate->addDay();
        }

        return response()->json([
            'message' => 'CDR import completed.',
            'total_imported' => $totalImported,
        ]);
    }

}

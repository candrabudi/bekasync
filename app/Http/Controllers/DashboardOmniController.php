<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardOmniController extends Controller
{
     public function index()
    {
        return view('dashboards.omni-channel.index');
    }

    private function getAccessToken()
    {
        $response = Http::asForm()->withHeaders([
            'Accept' => 'application/json',
        ])->post('https://cpapi.jasnita.co.id/api/v1/oauth/token', [
                    'grant_type' => 'password',
                    'client_id' => '38',
                    'client_secret' => '0xsNtqqNQx00S8SSqU8t3vlLwTGB30ekYAXRuhJN',
                    'username' => 'api@bekasikota.go.id',
                    'password' => '@D1skomB3k!!',
                    'scope' => '*',
                ]);

        return $response->json()['access_token'] ?? null;
    }

    public function activeAgent()
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken)
            return response()->json(['error' => 'Unauthorized'], 401);

        $response = Http::withToken($accessToken)
            ->get('https://cpapi.jasnita.co.id/api/v1/dashboard/active-agents');

        return response()->json([
            'active_agent' => $response->json()['active_agent'] ?? null,
        ]);
    }

    public function conversationsSummary(Request $request)
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken)
            return response()->json(['error' => 'Unauthorized'], 401);

        $startDate = $request->query('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', now()->endOfMonth()->toDateString());

        $response = Http::withToken($accessToken)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post('https://cpapi.jasnita.co.id/api/v1/dashboard/conversations-summary', [
                'date' => [$startDate, $endDate],
                'channel' => 'all',
            ]);

        return response()->json($response->json());
    }

    public function whatsappUsage(Request $request)
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $startDate = $request->query('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', now()->endOfMonth()->toDateString());

        $response = Http::withToken($accessToken)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post('https://cpapi.jasnita.co.id/api/v1/dashboard/wa-usage', [
                'date' => [$startDate, $endDate],
                'channel' => 'all',
            ]);

        return response()->json($response->json());
    }


    public function agentPerformance(Request $request)
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken)
            return response()->json(['error' => 'Unauthorized'], 401);

        $startDate = $request->query('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', now()->endOfMonth()->toDateString());

        $response = Http::withToken($accessToken)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post('https://cpapi.jasnita.co.id/api/v1/dashboard/agent-performance', [
                'date' => [$startDate, $endDate],
                'channel' => 'all',
            ]);

        return response()->json($response->json());
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardOmniController extends Controller
{
    protected $baseUrl = 'https://kotabekasiv2.sakti112.id/api/wallboard/sosmed';
    public function index()
    {
        return view('dashboards.omni-channel.index');
    }


    public function getActiveAgents(Request $request)
    {
        $response = Http::post("{$this->baseUrl}/get-active-agents", [
            'channel' => $request->channel,
            'date' => $request->date,
        ]);

        return $response->json();
    }

    public function getSubscription(Request $request)
    {
        $response = Http::post("{$this->baseUrl}/get-subscription-list", [
            'channel' => $request->channel,
            'date' => $request->date,
        ]);

        return $response->json();
    }

    public function getAgentPerformance(Request $request)
    {
        $response = Http::post("{$this->baseUrl}/get-agent-performance", [
            'channel' => $request->channel,
            'date' => $request->date,
        ]);

        return $response->json();
    }

    public function getConversationSummary(Request $request)
    {
        $response = Http::post("{$this->baseUrl}/get-conversation-summary", [
            'channel' => $request->channel,
            'date' => $request->date,
        ]);

        return $response->json();
    }

    public function getTopTags(Request $request)
    {
        $response = Http::post("{$this->baseUrl}/get-top-tags", [
            'channel' => $request->channel,
            'date' => $request->date,
        ]);

        return $response->json();
    }

    public function getWaUsage(Request $request)
    {
        $response = Http::post("{$this->baseUrl}/get-wa-usage", [
            'channel' => $request->channel,
            'date' => $request->date,
        ]);

        return $response->json();
    }
}

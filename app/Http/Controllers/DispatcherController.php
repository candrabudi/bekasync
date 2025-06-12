<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DispatcherController extends Controller
{
    public function index()
    {
        return view('dispatchers.index');
    }

    public function getDispatcherList(Request $request)
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

            $dispatcherResponse = Http::withHeaders([
                'accept' => '*/*',
                'X-CSRF-TOKEN' => '',
                'Authorization' => 'Bearer ' . $token,
            ])->get('https://kotabekasiv2.sakti112.id/api/v3/dispatcher/get-dispatcher');

            if (!$dispatcherResponse->successful()) {
                return response()->json(['error' => 'Gagal mengambil data dispatcher'], 500);
            }

            return response()->json($dispatcherResponse->json());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsAppMonitoringController extends Controller
{
    private $apiUrl;
    private $apiKey;

    public function __construct()
    {
        $this->apiUrl = env('EVOLUTION_URL');
        $this->apiKey = env('EVOLUTION_API_KEY');
    }

    // Menampilkan halaman dashboard dan tabel instance
    public function index()
    {
        $response = Http::withHeaders(['apikey' => $this->apiKey])
                        ->get($this->apiUrl . '/instance/fetchInstances');

        $instances = $response->successful() ? $response->json() : [];
        
        return view('admin.whatsapp-monitoring', compact('instances'));
    }

    // Mengambil Base64 QR Code untuk ditampilkan di Modal
    public function getQr($instanceName)
    {
        $response = Http::withHeaders(['apikey' => $this->apiKey])
                        ->get($this->apiUrl . '/instance/connect/' . $instanceName);

        return response()->json($response->json());
    }

    // Merestart Instance
    public function restart($instanceName)
    {
        Http::withHeaders(['apikey' => $this->apiKey])
            ->put($this->apiUrl . '/instance/restart/' . $instanceName);

        return back()->with('success', "Mesin '$instanceName' sedang di-restart.");
    }

    // Memutus koneksi / Logout Instance
    public function logout($instanceName)
    {
        Http::withHeaders(['apikey' => $this->apiKey])
            ->delete($this->apiUrl . '/instance/logout/' . $instanceName);

        return back()->with('warning', "Koneksi '$instanceName' berhasil diputus.");
    }
}
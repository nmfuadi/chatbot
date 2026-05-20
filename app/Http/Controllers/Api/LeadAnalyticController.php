<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\LeadAnalytic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Services\TrackingService; // <-- Jangan lupa import di atas class

class LeadAnalyticController extends Controller
{
    // Menampilkan Halaman Kanban & Summary
    public function index()
    {
        // 1. Ambil ID member yang sedang login
        $userId = \Illuminate\Support\Facades\Auth::id();

        // 2. Query Utama: Ambil DATA TERBARU (MAX id) dari lead_analytics per chat_session_id MILIK user yang login
        $leadsData = \App\Models\LeadAnalytic::select('lead_analytics.*')
            ->join('chat_sessions', 'lead_analytics.chat_session_id', '=', 'chat_sessions.id')
            ->where('chat_sessions.user_id', $userId)
            ->whereIn('lead_analytics.id', function ($query) {
                $query->selectRaw('MAX(id)')
                      ->from('lead_analytics')
                      ->groupBy('chat_session_id');
            })
            ->orderBy('lead_analytics.updated_at', 'desc')
            ->get();

        // 3. Kelompokkan data yang sudah bersih berdasarkan status untuk papan Kanban
        // (Pastikan key grouping ini sesuai dengan nama kolom status di database Kakak, misal 'status_prospek')
        $leads = $leadsData->groupBy('status_prospek'); 

        // 4. Hitung Statistik Berdasarkan Data Ter-filter (Hanya Data Milik User & Status Terbaru)
        $totalLeads   = $leadsData->count();
        
        // Menghitung jumlah per status dari hasil query yang sudah difilter di atas
        $hotLeads     = $leadsData->where('status_prospek', 'hot_prospek')->count();
        $closingLeads = $leadsData->where('status_prospek', 'closing')->count();
        $gagalLeads   = $leadsData->where('status_prospek', 'gagal')->count();

        // 5. Return View dengan seluruh compact bawaan asli agar tidak error
        return view('analytics.kanban', compact(
            'totalLeads', 'closingLeads', 'hotLeads', 'gagalLeads', 'leads'
        ));
    }

    // Menerima aksi Drag & Drop dari SortableJS
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id'             => 'required|exists:lead_analytics,id',
            'status_prospek' => 'required|string',
            'chat_summary'   => 'required|string',
            'alasan_batal'   => 'nullable|string'
        ]);

        // 1. Ambil data analitik lama untuk menduplikasi informasi penting (nomor, instance, dll)
        $oldLead = \App\Models\LeadAnalytic::find($request->id);

        // 2. Buat BARIS BARU sebagai log riwayat terbaru dari pemindahan manual member
        $newLog = \App\Models\LeadAnalytic::create([
            'chat_session_id' => $oldLead->chat_session_id,
            'phone'           => $oldLead->phone,
            'instance'        => $oldLead->instance,
            'sumber_iklan'    => $oldLead->sumber_iklan,
            'status_prospek'  => $request->status_prospek,
            'chat_summary'    => $request->chat_summary,
            'lead_score'      => $request->status_prospek === 'closing' ? 100 : ($request->status_prospek === 'gagal' ? 0 : $oldLead->lead_score),
            'alasan_batal'    => $request->status_prospek === 'gagal' ? $request->alasan_batal : null,
            'buyer_character' => $request->buyer_character, // <-- COPIED DARI DATA SEBELUMNYA
        ]);

        $value = $request->status_prospek === 'closing' ? 100000 : 0;
    
    TrackingService::dispatch(
        $oldLead->user_id, // Pastikan Anda punya akses ke user_id
        $oldLead->phone, 
        $request->status_prospek,
        $value
    );

        return response()->json([
            'success' => true,
            'message' => 'Status dan riwayat baru berhasil dicatat',
            'data'    => $newLog
        ]);
    }

    public function store(Request $request)
    {
        // 1. Validasi data masuk dari n8n
        $validator = Validator::make($request->all(), [
            'phone'          => 'required|string',
            'instance'       => 'required|string',
            'status_prospek' => 'required|string|in:baru,prospect,hot_prospek,deal,closing,gagal',
            'alasan_batal'   => 'nullable|string',
            'sumber_iklan'   => 'nullable|string',
            'chat_summary'   => 'nullable|string',
            'lead_score'     => 'nullable|integer',
            'buyer_character' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        // 2. Transaksi Database (Simpan Log & Update Status Master)
        DB::beginTransaction();
        try {
            // A. CARI ID MASTER (CHAT SESSION) BERDASARKAN NOMOR HP
            // Bersihkan format nomor dari @s.whatsapp.net untuk pencarian yang lebih akurat
            $cleanPhone = str_replace('@s.whatsapp.net', '', $request->phone);
            
            // Asumsi model Anda berada di namespace default App\Models
            $masterChat = \App\Models\ChatSession::where('customer_phone', $request->phone)
                                                 ->orWhere('customer_phone', $cleanPhone)
                                                 ->first();
            
            // Ambil ID-nya jika ketemu, jika tidak maka null
            $chatSessionId = $masterChat ? $masterChat->id : null;


            // B. UPDATE ATAU CREATE DATA DI LEAD ANALYTICS
            // Menggunakan updateOrCreate agar 1 Nomor HP = 1 Kartu saja
            // B. BUAT DATA BARU DI LEAD ANALYTICS (SEBAGAI LOG RIWAYAT)
            $analytics = LeadAnalytic::create([
                'chat_session_id' => $chatSessionId,
                'phone'           => $request->phone,
                'instance'        => $request->instance,
                'status_prospek'  => $request->status_prospek,
                'alasan_batal'    => $request->alasan_batal == 'null' ? null : $request->alasan_batal,
                'sumber_iklan'    => $request->sumber_iklan ?? 'Organik',
                'chat_summary'    => $request->chat_summary ?? 'Belum ada ringkasan', 
                'lead_score'      => $request->lead_score ?? 0, 
                'buyer_character' => $request->buyer_character, // <-- SUNTIKKAN INI
            ]);

            DB::commit();
           // CARI USER BERDASARKAN INSTANCE WA
        $user = \App\Models\User::where('wablas_device_id', $request->instance)->first();
    
           // JIKA USER DITEMUKAN, JALANKAN TRACKING
        if ($user) {
            $value = $request->status_prospek === 'closing' ? 100000 : 0; 
            
            \App\Services\TrackingService::dispatch(
                $user->id, 
                $request->phone, 
                $request->status_prospek,
                $value
            );
        }

            return response()->json([
                'success' => true,
                'message' => 'Lead analytics data tracked successfully',
                'data'    => $analytics
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to track data: ' . $e->getMessage()
            ], 500);
        }
    }


    


    // Fungsi untuk menarik riwayat berdasarkan nomor HP
    public function history($phone)
    {
        $history = LeadAnalytic::where('phone', $phone)
            ->orderBy('created_at', 'desc') // Urutkan dari yang terbaru
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $history
        ]);
    }

    // ====================================================================
    // --- FITUR AUTO PAUSE & RESUME AI (HUMAN TAKEOVER) ---
    // ====================================================================

    /**
     * Mematikan AI secara otomatis saat Owner membalas chat secara manual
     */
    

    /**
     * Menyalakan kembali AI saat Owner mengetik perintah #s
     */
    // ====================================================================
    // --- FITUR AUTO PAUSE & RESUME AI (FIXED SCHEMA CHAT SESSIONS) ---
    // ====================================================================

    /**
     * Mematikan AI secara otomatis (is_ai_active = 0) saat Owner membalas manual
     */
    public function pauseAi(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'phone' => 'required'
        ]);

        $phoneInput = $request->phone; // Ambil input phone dari n8n
        $cleanPhone = str_replace('@s.whatsapp.net', '', $phoneInput);

        // Pencarian fleksibel untuk mencakup format nomor biasa atau yang pakai @s.whatsapp.net
        $session = \App\Models\ChatSession::where('customer_phone', $phoneInput)
                                          ->orWhere('customer_phone', $cleanPhone)
                                          ->orWhere('customer_phone', $cleanPhone . '@s.whatsapp.net')
                                          ->first();

        if ($session) {
            // Set menjadi 0 (Non-Aktif) sesuai tipe data integer di tabel data
            $session->update(['is_ai_active' => 0]);
            
            return response()->json([
                'success' => true,
                'message' => 'AI berhasil di-pause (is_ai_active = 0) untuk customer: ' . $cleanPhone
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Session chat tidak ditemukan.'], 404);
    }

    /**
     * Menyalakan kembali AI (is_ai_active = 1) saat Owner mengetik perintah #s
     */
    public function resumeAi(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'phone' => 'required'
        ]);

        $phoneInput = $request->phone;
        $cleanPhone = str_replace('@s.whatsapp.net', '', $phoneInput);

        $session = \App\Models\ChatSession::where('customer_phone', $phoneInput)
                                          ->orWhere('customer_phone', $cleanPhone)
                                          ->orWhere('customer_phone', $cleanPhone . '@s.whatsapp.net')
                                          ->first();

        if ($session) {
            // Set menjadi 1 (Aktif Kembali)
            $session->update(['is_ai_active' => 1]);
            
            return response()->json([
                'success' => true,
                'message' => 'AI berhasil diaktifkan kembali (is_ai_active = 1) untuk customer: ' . $cleanPhone
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Session chat tidak ditemukan.'], 404);
    }


    
}

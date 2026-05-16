<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\LeadAnalytic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LeadAnalyticController extends Controller
{
    // Menampilkan Halaman Kanban & Summary
    public function index()
    {
        // 1. Ambil Summary Data
        $totalLeads = \App\Models\LeadAnalytic::count();
        $closingLeads = \App\Models\LeadAnalytic::where('status_prospek', 'closing')->count();
        $hotLeads = \App\Models\LeadAnalytic::where('status_prospek', 'hot_prospek')->count();
        $gagalLeads = \App\Models\LeadAnalytic::where('status_prospek', 'gagal')->count();

        // 2. Ambil semua leads, kelompokkan berdasarkan status
        // Catatan: Jika ada filter per instance/member login, tambahkan ->where('instance', Auth::user()->instance)
        $leads = \App\Models\LeadAnalytic::latest()->get()->groupBy('status_prospek');

        return view('analytics.kanban', compact(
            'totalLeads', 'closingLeads', 'hotLeads', 'gagalLeads', 'leads'
        ));
    }

    // Menerima aksi Drag & Drop dari SortableJS
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:lead_analytics,id',
            'status_prospek' => 'required|string'
        ]);

        $lead = \App\Models\LeadAnalytic::find($request->id);
        $lead->status_prospek = $request->status_prospek;
        $lead->save();

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui'
        ]);
    }

    public function store(Request $request)
    {
        // 1. Validasi data masuk dari n8n
        $validator = Validator::make($request->all(), [
            'phone'          => 'required|string',
            'instance'       => 'required|string',
            'status_prospek' => 'required|string|in:baru,tanya_harga,hot_prospek,closing,gagal',
            'alasan_batal'   => 'nullable|string',
            'sumber_iklan'   => 'nullable|string',
            'chat_summary'   => 'nullable|string',
            'lead_score'     => 'nullable|integer',
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
            $analytics = LeadAnalytic::updateOrCreate(
                [
                    // Kriteria pencarian: Cari berdasarkan nomor HP dan instance ini
                    'phone'    => $request->phone,
                    'instance' => $request->instance,
                ],
                [
                    // Nilai yang akan di-update (atau di-insert jika datanya baru)
                    'chat_session_id' => $chatSessionId,
                    'status_prospek'  => $request->status_prospek,
                    'alasan_batal'    => $request->alasan_batal == 'null' ? null : $request->alasan_batal,
                    'sumber_iklan'    => $request->sumber_iklan ?? 'Organik',
                    'chat_summary'    => $request->chat_summary ?? 'Belum ada ringkasan', 
                    'lead_score'      => $request->lead_score ?? 0, 
                ]
            );

            DB::commit();

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
}

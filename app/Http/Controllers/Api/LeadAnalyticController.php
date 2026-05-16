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
        // 1. Cari ID terakhir untuk masing-masing nomor HP
        $latestIds = LeadAnalytic::selectRaw('MAX(id) as id')
            ->groupBy('phone')
            ->pluck('id');

        // 2. Ambil data lengkapnya HANYA untuk ID yang terbaru tadi
        $latestLeads = LeadAnalytic::whereIn('id', $latestIds)->latest()->get();

        // 3. Hitung Summary berdasarkan data terbaru
        $totalLeads = $latestLeads->count();
        $closingLeads = $latestLeads->where('status_prospek', 'closing')->count();
        $hotLeads = $latestLeads->where('status_prospek', 'hot_prospek')->count();
        $gagalLeads = $latestLeads->where('status_prospek', 'gagal')->count();

        // 4. Kelompokkan untuk papan Kanban
        $leads = $latestLeads->groupBy('status_prospek');

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
            ]);

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
}

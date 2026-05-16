<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\LeadAnalytic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LeadAnalyticController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi data masuk dari n8n
        $validator = Validator::make($request->all(), [
            'phone'          => 'required|string',
            'instance'       => 'required|string',
            'status_prospek' => 'required|string|in:baru,tanya_harga,hot_prospek,closing,gagal',
            'alasan_batal'   => 'nullable|string',
            'sumber_iklan'   => 'nullable|string',
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
            // Simpan data ke log analitik
            $analytics = LeadAnalytic::create([
                'phone'          => $request->phone,
                'instance'       => $request->instance,
                'status_prospek' => $request->status_prospek,
                'alasan_batal'   => $request->alasan_batal == 'null' ? null : $request->alasan_batal,
                'sumber_iklan'   => $request->sumber_iklan ?? 'Organik',
            ]);

            // OPTIONAL LOGIC: Jika Anda punya tabel master 'customers', update statusnya di sini
            // DB::table('customers')
            //     ->where('phone', $request->phone)
            //     ->where('instance', $request->instance)
            //     ->update(['current_status' => $request->status_prospek]);

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

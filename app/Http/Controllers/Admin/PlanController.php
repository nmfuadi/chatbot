<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'max_messages' => 'required|integer',
            'max_sop_chars' => 'required|integer',
            'features' => 'nullable|string',
        ]);

        // Mengubah string textarea (per baris) menjadi array
        $featuresArray = $request->features ? explode("\n", str_replace("\r", "", $request->features)) : [];

        Plan::create([
            'name' => $request->name,
            'price' => $request->price,
            'max_messages' => $request->max_messages,
            'is_unlimited_messages' => $request->has('is_unlimited_messages'),
            'max_sop_chars' => $request->max_sop_chars,
            'max_wa_numbers' => $request->max_wa_numbers ?? 1,
            'features' => $featuresArray,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.plans.index')->with('success', 'Paket berhasil dibuat!');
    }

    public function edit(Plan $plan)
    {
        // Gabungkan array features menjadi string untuk ditampilkan di textarea
        $featuresString = is_array($plan->features) ? implode("\n", $plan->features) : '';
        return view('admin.plans.edit', compact('plan', 'featuresString'));
    }

    public function update(Request $request, Plan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'max_messages' => 'required|integer',
            'max_sop_chars' => 'required|integer',
        ]);

        $featuresArray = $request->features ? explode("\n", str_replace("\r", "", $request->features)) : [];

        $plan->update([
            'name' => $request->name,
            'price' => $request->price,
            'max_messages' => $request->max_messages,
            'is_unlimited_messages' => $request->has('is_unlimited_messages'),
            'max_sop_chars' => $request->max_sop_chars,
            'max_wa_numbers' => $request->max_wa_numbers,
            'features' => $featuresArray,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.plans.index')->with('success', 'Paket berhasil diperbarui!');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return redirect()->route('admin.plans.index')->with('success', 'Paket berhasil dihapus!');
    }
}
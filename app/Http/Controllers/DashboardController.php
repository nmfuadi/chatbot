<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman utama Dashboard
     */
    public function index()
    {
        // Memanggil file resources/views/dashboard.blade.php
        return view('dashboard'); 
    }
}
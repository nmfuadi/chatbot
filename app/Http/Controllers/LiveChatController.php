<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LiveChatController extends Controller
{
    public function index()
    {
        // Nanti kita akan melempar data chat sessions ke sini
        return view('member.livechat'); 
    }
}
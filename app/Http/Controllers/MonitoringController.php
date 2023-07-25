<?php

namespace App\Http\Controllers;

class MonitoringController extends Controller
{
    public function timeline()
    {
        $logs = MonitoringLog::orderBy('created_at')->get();

        return view('timeline', compact('logs'));
    }
}

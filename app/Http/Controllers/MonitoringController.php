<?php

namespace App\Http\Controllers;

use App\Models\MonitoringLog;

class MonitoringController extends Controller
{
    public function timeline()
    {
        $logs = MonitoringLog::orderBy('created_at')->get();

        return view('timeline', compact('logs'));
    }
}

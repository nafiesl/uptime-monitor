<?php

namespace App\Http\Controllers;

use App\Models\MonitoringLog;

class MonitoringController extends Controller
{
    public function timeline()
    {
        $logs = MonitoringLog::orderBy('created_at', 'desc')->paginate(100);

        return view('timeline', compact('logs'));
    }
}

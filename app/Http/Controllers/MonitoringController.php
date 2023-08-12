<?php

namespace App\Http\Controllers;

use App\Models\CustomerSite;
use App\Models\MonitoringLog;

class MonitoringController extends Controller
{
    public function index()
    {
        $customerSites = CustomerSite::orderBy('name')->get();

        return view('monitoring.index', compact('customerSites'));
    }

    public function test()
    {
        $logQuery = MonitoringLog::query();
        $logQuery->where('customer_site_id', 3);
        $logQuery->whereBetween('created_at', ['2023-08-01', '2023-08-31']);
        $monitoringLogs = $logQuery->get(['response_time', 'created_at']);

        $chartData = [];
        foreach ($monitoringLogs as $monitoringLog) {
            $chartData[] = ['x' => $monitoringLog->created_at, 'y' => $monitoringLog->response_time];
        }
        // dd($chartData);

        return view('test', compact('chartData'));
    }

    public function timeline()
    {
        $customerSites = CustomerSite::orderBy('name')->pluck('name', 'id');
        $logQuery = MonitoringLog::query();
        if (request('customer_site_id')) {
            $logQuery->where('customer_site_id', request('customer_site_id'));
        }
        $logs = $logQuery->orderBy('created_at', 'desc')->with('customerSite')->paginate(100);

        return view('timeline', compact('logs', 'customerSites'));
    }
}

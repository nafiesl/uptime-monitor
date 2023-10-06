<?php

namespace App\Http\Controllers;

use App\Models\CustomerSite;
use App\Models\MonitoringLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomerSiteController extends Controller
{
    public function index(Request $request)
    {
        $customerSiteQuery = CustomerSite::query();
        $customerSiteQuery->where('name', 'like', '%'.$request->get('q').'%');
        $customerSiteQuery->orderBy('name');
        $customerSites = $customerSiteQuery->paginate(25);

        return view('customer_sites.index', compact('customerSites'));
    }

    public function create()
    {
        $this->authorize('create', new CustomerSite);

        return view('customer_sites.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', new CustomerSite);

        $newCustomerSite = $request->validate([
            'name' => 'required|max:60',
            'url' => 'required|max:255',
        ]);
        $newCustomerSite['creator_id'] = auth()->id();

        $customerSite = CustomerSite::create($newCustomerSite);

        return redirect()->route('customer_sites.show', $customerSite);
    }

    public function show(Request $request, CustomerSite $customerSite)
    {
        $startTime = Carbon::now()->subHour();
        $timeRange = request('time_range', '1h');
        switch ($timeRange) {
            case '6h':
                $startTime = Carbon::now()->subHours(6);
                break;
            case '24h':
                $startTime = Carbon::now()->subHours(24);
                break;
            case '7d':
                $startTime = Carbon::now()->subDays(7);
                break;
            case '14d':
                $startTime = Carbon::now()->subDays(14);
                break;
            case '30d':
                $startTime = Carbon::now()->subDays(30);
                break;
            case '3Mo':
                $startTime = Carbon::now()->subMonths(3);
                break;
            case '6Mo':
                $startTime = Carbon::now()->subMonths(6);
                break;
            default:
                $startTime = Carbon::now()->subHours(1);
                break;
        }
        if ($request->get('start_time')) {
            $timeRange = null;
            $startTime = Carbon::parse($request->get('start_time'));
        }
        $endTime = Carbon::now();
        if ($request->get('start_time')) {
            $endTime = Carbon::parse($request->get('end_time'));
        }
        $logQuery = MonitoringLog::query();
        $logQuery->where('customer_site_id', $customerSite->id);
        $logQuery->whereBetween('created_at', [$startTime, $endTime]);
        $monitoringLogs = $logQuery->get(['response_time', 'created_at']);

        $chartData = [];
        foreach ($monitoringLogs as $monitoringLog) {
            $chartData[] = ['x' => $monitoringLog->created_at, 'y' => $monitoringLog->response_time];
        }

        return view('customer_sites.show', compact('customerSite', 'chartData', 'startTime', 'endTime', 'timeRange'));
    }

    public function edit(CustomerSite $customerSite)
    {
        $this->authorize('update', $customerSite);

        return view('customer_sites.edit', compact('customerSite'));
    }

    public function update(Request $request, CustomerSite $customerSite)
    {
        $this->authorize('update', $customerSite);

        $customerSiteData = $request->validate([
            'name' => 'required|max:60',
            'url' => 'required|max:255',
            'is_active' => 'required|in:0,1',
        ]);
        $customerSite->update($customerSiteData);

        return redirect()->route('customer_sites.show', $customerSite);
    }

    public function destroy(Request $request, CustomerSite $customerSite)
    {
        $this->authorize('delete', $customerSite);

        $request->validate(['customer_site_id' => 'required']);

        if ($request->get('customer_site_id') == $customerSite->id && $customerSite->delete()) {
            return redirect()->route('customer_sites.index');
        }

        return back();
    }
}

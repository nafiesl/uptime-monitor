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
        if ($request->get('start_time')) {
            $startTime = Carbon::parse($request->get('start_time'));
        }
        if ($request->get('start_timestamp')) {
            $startTime = Carbon::createFromTimestamp($request->get('start_timestamp'));
        }
        $endTime = Carbon::now();
        if ($request->get('start_time')) {
            $endTime = Carbon::parse($request->get('end_time'));
        }
        if ($request->get('start_timestamp')) {
            $endTime = Carbon::createFromTimestamp($request->get('end_timestamp'));
        }
        $logQuery = MonitoringLog::query();
        $logQuery->where('customer_site_id', $customerSite->id);
        $logQuery->whereBetween('created_at', [$startTime, $endTime]);
        $monitoringLogs = $logQuery->get(['response_time', 'created_at']);

        $chartData = [];
        foreach ($monitoringLogs as $monitoringLog) {
            $chartData[] = ['x' => $monitoringLog->created_at, 'y' => $monitoringLog->response_time];
        }

        return view('customer_sites.show', compact('customerSite', 'chartData', 'startTime', 'endTime'));
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

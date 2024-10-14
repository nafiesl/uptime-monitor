<?php

namespace App\Http\Controllers;

use App\Jobs\RunCheck;
use App\Models\CustomerSite;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CustomerSiteController extends Controller
{
    public function index(Request $request)
    {
        $availableVendors = Vendor::orderBy('name')->pluck('name', 'id')->toArray();
        $availableVendors = ['null' => 'n/a'] + $availableVendors;

        $customerSiteQuery = CustomerSite::query();
        $customerSiteQuery->where('name', 'like', '%'.$request->get('q').'%');
        $customerSiteQuery->orderBy('name');
        $customerSiteQuery->where('owner_id', auth()->id());
        if ($vendorId = $request->get('vendor_id')) {
            if ($vendorId == 'null') {
                $customerSiteQuery->whereNull('vendor_id');
            } else {
                $customerSiteQuery->where('vendor_id', $vendorId);
            }
        }
        $customerSites = $customerSiteQuery->with('vendor')->paginate(25);

        return view('customer_sites.index', compact('customerSites', 'availableVendors'));
    }

    public function create()
    {
        $this->authorize('create', new CustomerSite);
        $availableVendors = Vendor::orderBy('name')->pluck('name', 'id');

        return view('customer_sites.create', compact('availableVendors'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', new CustomerSite);

        $newCustomerSite = $request->validate([
            'name' => 'required|max:60',
            'webhook_url' => 'nullable',
            'url' => 'required|max:255',
            'vendor_id' => 'nullable|exists:vendors,id',
        ]);
        $newCustomerSite['owner_id'] = auth()->id();

        $customerSite = CustomerSite::create($newCustomerSite);

        return redirect()->route('customer_sites.show', $customerSite);
    }

    public function show(Request $request, CustomerSite $customerSite)
    {
        $timeRange = request('time_range', '1h');
        $startTime = $this->getStartTimeByTimeRage($timeRange);
        if ($request->get('start_time')) {
            $timeRange = null;
            $startTime = Carbon::parse($request->get('start_time'));
        }
        $endTime = Carbon::now();
        if ($request->get('start_time')) {
            $endTime = Carbon::parse($request->get('end_time'));
        }
        $logQuery = DB::table('monitoring_logs');
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
        $availableVendors = Vendor::orderBy('name')->pluck('name', 'id');

        return view('customer_sites.edit', compact('customerSite', 'availableVendors'));
    }

    public function update(Request $request, CustomerSite $customerSite)
    {
        $this->authorize('update', $customerSite);

        $customerSiteData = $request->validate([
            'name' => 'required|max:60',
            'url' => 'required|max:255',
            'webhook_url' => 'nullable',
            'vendor_id' => 'nullable|exists:vendors,id',
            'is_active' => 'required|in:0,1',
            'check_interval' => ['required', 'numeric', 'min:1', 'max:60'],
            'priority_code' => 'required|in:high,normal,low',
            'warning_threshold' => ['required', 'numeric', 'min:1000', 'max:30000'],
            'down_threshold' => ['required', 'numeric', 'min:2000', 'max:60000'],
            'notify_user_interval' => ['required', 'numeric', 'min:0', 'max:60'],
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

    public function timeline(Request $request, CustomerSite $customerSite)
    {
        $timeRange = request('time_range', '1h');
        $startTime = $this->getStartTimeByTimeRage($timeRange);
        if ($request->get('start_time')) {
            $timeRange = null;
            $startTime = Carbon::parse($request->get('start_time'));
        }
        $endTime = Carbon::now();
        if ($request->get('start_time')) {
            $endTime = Carbon::parse($request->get('end_time'));
        }
        $logQuery = DB::table('monitoring_logs');
        $logQuery->where('customer_site_id', $customerSite->id);
        $logQuery->whereBetween('created_at', [$startTime, $endTime]);
        $monitoringLogs = $logQuery->latest()->paginate(60);

        return view('customer_sites.timeline', compact('customerSite', 'monitoringLogs', 'startTime', 'endTime', 'timeRange'));
    }

    private function getStartTimeByTimeRage(string $timeRange): Carbon
    {
        switch ($timeRange) {
            case '6h':return Carbon::now()->subHours(6);
            case '24h':return Carbon::now()->subHours(24);
            case '7d':return Carbon::now()->subDays(7);
            case '14d':return Carbon::now()->subDays(14);
            case '30d':return Carbon::now()->subDays(30);
            case '3Mo':return Carbon::now()->subMonths(3);
            case '6Mo':return Carbon::now()->subMonths(6);
            default:return Carbon::now()->subHours(1);
        }
    }

    public function checkNow(Request $request, CustomerSite $customerSite)
    {
        RunCheck::dispatch($customerSite);

        return back();
    }
}

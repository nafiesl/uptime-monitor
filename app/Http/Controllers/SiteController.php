<?php

namespace App\Http\Controllers;

use App\Jobs\RunCheck;
use App\Models\Site;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SiteController extends Controller
{
    public function index(Request $request)
    {
        $availableVendors = Vendor::orderBy('name')->pluck('name', 'id')->toArray();
        $availableVendors = ['null' => 'n/a'] + $availableVendors;

        $siteQuery = Site::query();
        $siteQuery->where('name', 'like', '%'.$request->get('q').'%');
        $siteQuery->orderBy('name');
        $siteQuery->where('owner_id', auth()->id());
        if ($vendorId = $request->get('vendor_id')) {
            if ($vendorId == 'null') {
                $siteQuery->whereNull('vendor_id');
            } else {
                $siteQuery->where('vendor_id', $vendorId);
            }
        }
        $sites = $siteQuery->with('vendor')->paginate(25);

        return view('sites.index', compact('sites', 'availableVendors'));
    }

    public function create()
    {
        $this->authorize('create', new Site);
        $availableVendors = Vendor::orderBy('name')->pluck('name', 'id');

        return view('sites.create', compact('availableVendors'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', new Site);

        $newSite = $request->validate([
            'name' => 'required|max:60',
            'url' => 'required|max:255',
            'vendor_id' => 'nullable|exists:vendors,id',
        ]);
        $newSite['owner_id'] = auth()->id();

        $site = Site::create($newSite);

        return redirect()->route('sites.show', $site);
    }

    public function show(Request $request, Site $site)
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
        $logQuery->where('site_id', $site->id);
        $logQuery->whereBetween('created_at', [$startTime, $endTime]);
        $monitoringLogs = $logQuery->get(['response_time', 'created_at']);

        $chartData = [];
        foreach ($monitoringLogs as $monitoringLog) {
            $chartData[] = ['x' => $monitoringLog->created_at, 'y' => $monitoringLog->response_time];
        }

        return view('sites.show', compact('site', 'chartData', 'startTime', 'endTime', 'timeRange'));
    }

    public function edit(Site $site)
    {
        $this->authorize('update', $site);
        $availableVendors = Vendor::orderBy('name')->pluck('name', 'id');

        return view('sites.edit', compact('site', 'availableVendors'));
    }

    public function update(Request $request, Site $site)
    {
        $this->authorize('update', $site);

        $siteData = $request->validate([
            'name' => 'required|max:60',
            'url' => 'required|max:255',
            'vendor_id' => 'nullable|exists:vendors,id',
            'is_active' => 'required|in:0,1',
            'check_interval' => ['required', 'numeric', 'min:1', 'max:60'],
            'priority_code' => 'required|in:high,normal,low',
            'warning_threshold' => ['required', 'numeric', 'min:1000', 'max:30000'],
            'down_threshold' => ['required', 'numeric', 'min:2000', 'max:60000'],
            'notify_user_interval' => ['required', 'numeric', 'min:0', 'max:60'],
        ]);
        $site->update($siteData);

        return redirect()->route('sites.show', $site);
    }

    public function destroy(Request $request, Site $site)
    {
        $this->authorize('delete', $site);

        $request->validate(['site_id' => 'required']);

        if ($request->get('site_id') == $site->id && $site->delete()) {
            return redirect()->route('sites.index');
        }

        return back();
    }

    public function timeline(Request $request, Site $site)
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
        $logQuery->where('site_id', $site->id);
        $logQuery->whereBetween('created_at', [$startTime, $endTime]);
        $monitoringLogs = $logQuery->latest()->paginate(60);

        return view('sites.timeline', compact('site', 'monitoringLogs', 'startTime', 'endTime', 'timeRange'));
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

    public function checkNow(Request $request, Site $site)
    {
        RunCheck::dispatch($site);

        return back();
    }
}

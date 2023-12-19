<?php

namespace App\Http\Controllers;

use App\Models\CustomerSite;
use App\Models\MonitoringLog;
use App\Models\Vendor;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $customerSiteQuery = CustomerSite::query();
        $customerSiteQuery->where('is_active', 1);
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
        $customerSites = $customerSiteQuery->get();

        $availableVendors = Vendor::orderBy('name')->pluck('name', 'id')->toArray();
        $availableVendors = ['null' => 'n/a'] + $availableVendors;

        return view('monitoring.index', compact('customerSites', 'availableVendors'));
    }

    public function timeline()
    {
        $customerSites = CustomerSite::orderBy('name')->pluck('name', 'id');
        $logQuery = MonitoringLog::query();
        if (request('customer_site_id')) {
            $logQuery->where('customer_site_id', request('customer_site_id'));
        }
        $logs = $logQuery->orderBy('created_at', 'desc')->with('customerSite')->paginate(60);

        return view('timeline', compact('logs', 'customerSites'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Vendor;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $siteQuery = Site::query();
        $siteQuery->where('is_active', 1);
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
        $sites = $siteQuery->with('vendor')->get();

        $availableVendors = Vendor::orderBy('name')->pluck('name', 'id')->toArray();
        $availableVendors = ['null' => 'n/a'] + $availableVendors;

        return view('monitoring.index', compact('sites', 'availableVendors'));
    }

    public function index2(Request $request)
    {
        $siteQuery = Site::query();
        $siteQuery->where('is_active', 1);
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
        $sites = $siteQuery->with('vendor')->get();

        $availableVendors = Vendor::orderBy('name')->pluck('name', 'id')->toArray();
        $availableVendors = ['null' => __('vendor.without_vendor')] + $availableVendors;

        $vendors = Vendor::get();

        return view('monitoring.index2', compact('sites', 'availableVendors' , 'vendors'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\CustomerSite;
use App\Models\Vendor;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $customerSiteQuery = CustomerSite::query();
        $customerSiteQuery->where('is_active', 1);
        $customerSiteQuery->where('name', 'like', '%'.$request->get('q').'%');
        $customerSiteQuery->orderBy('type_id');
        $customerSiteQuery->orderBy('name');
        $customerSiteQuery->where('owner_id', auth()->id());
        if ($vendorId = $request->get('vendor_id')) {
            if ($vendorId == 'null') {
                $customerSiteQuery->whereNull('vendor_id');
            } else {
                $customerSiteQuery->where('vendor_id', $vendorId);
            }
        }
        $customerSites = $customerSiteQuery->with('vendor')->get();

        $availableVendors = Vendor::orderBy('name')->pluck('name', 'id')->toArray();
        $availableVendors = ['null' => 'n/a'] + $availableVendors;

        $theBlock = array();
        $theCustomerSites = array();
        foreach ($customerSites as $site) {
            $theBlock[$site->type->name][] = $site;
        }

        foreach ($theBlock as $theEnv => $site) {
            $theCustomerSites[] = [
                'env' => $theEnv,
                'data' => $site
            ];
        }

        
        return view('monitoring.index', compact('theCustomerSites', 'availableVendors'));
    }
}

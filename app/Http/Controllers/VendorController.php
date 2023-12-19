<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $vendorQuery = Vendor::query();
        $vendorQuery->where('name', 'like', '%'.$request->get('q').'%');
        $vendorQuery->orderBy('name');
        $vendors = $vendorQuery->paginate(25);

        return view('vendors.index', compact('vendors'));
    }

    public function create()
    {
        $this->authorize('create', new Vendor);

        return view('vendors.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', new Vendor);

        $newVendor = $request->validate([
            'name' => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);
        $newVendor['creator_id'] = auth()->id();

        $vendor = Vendor::create($newVendor);

        return redirect()->route('vendors.show', $vendor);
    }

    public function show(Vendor $vendor)
    {
        return view('vendors.show', compact('vendor'));
    }

    public function edit(Vendor $vendor)
    {
        $this->authorize('update', $vendor);

        return view('vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $this->authorize('update', $vendor);

        $vendorData = $request->validate([
            'name' => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);
        $vendor->update($vendorData);

        return redirect()->route('vendors.show', $vendor);
    }

    public function destroy(Request $request, Vendor $vendor)
    {
        $this->authorize('delete', $vendor);

        $request->validate(['vendor_id' => 'required']);

        if ($request->get('vendor_id') == $vendor->id && $vendor->delete()) {
            return redirect()->route('vendors.index');
        }

        return back();
    }
}

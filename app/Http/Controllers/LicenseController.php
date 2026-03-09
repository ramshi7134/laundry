<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\License;

class LicenseController extends Controller
{
    public function showActivateForm()
    {
        return view('license.activate');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'license_key' => 'required|string',
        ]);

        $branchId = config('pos.branch_id', 1);

        // Simple mock verification logic for demonstration purposes
        // In reality, this would curl a central API server to valid the license details
        if (strlen($request->license_key) > 8) {
            $license = License::firstOrNew(['branch_id' => $branchId]);
            $license->license_key = $request->license_key;
            $license->valid_from = now();
            $license->valid_until = now()->addYear();
            $license->status = 'active';
            $license->save();

            return redirect()->route('pos.index')->with('success', 'License activated successfully!');
        }

        return back()->with('error', 'Invalid License Key provided.');
    }
}

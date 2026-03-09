<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\License;

class CheckLicense
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // For demonstration, let's assume branch 1 is the default locally running instance.
        // In reality, this might be tied to a global config or env setup.
        $branchId = config('pos.branch_id', 1);

        $license = License::where('branch_id', $branchId)->first();

        if (!$license || !$license->isValid()) {
            // Redirect to a license activation view
            return redirect()->route('license.activate');
        }

        return $next($request);
    }
}

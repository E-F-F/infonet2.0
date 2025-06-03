<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\StaffAccess; // Assuming StaffAccess model exists and is in App\Models
use App\Models\SystemAccess; // Assuming SystemAccess model exists and is in App\Models

class CheckSystemAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $systemModule
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $systemModule): Response
    {
        // Check staff authentication
        if (!Auth::guard('staff')->check()) {
            // abort(403, 'Your account does not have any assigned system access permissions.');
            return redirect()->route('staff.login')->with('error', 'Please log in to access this resource.');
        }

        $staffAuthId = Auth::guard('staff')->id();

        // Fetch staff access record
        $staffAccess = StaffAccess::where('staff_auth_id', $staffAuthId)->first();
        if (!$staffAccess) {
            abort(403, 'Your account does not have any assigned system access permissions.');
        }

        // Fetch detailed access
        $systemAccess = SystemAccess::find($staffAccess->system_access_id);
        if (!$systemAccess || empty($systemAccess->$systemModule)) {
            abort(403, 'You do not have access to the ' . ucfirst($systemModule) . ' module.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class CheckModuleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $module The name of the module to check access for (e.g., 'hrms', 'ims', 'fms').
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        // Debug 1: Check if any user is authenticated
        if (!Auth::check()) {
            // If not authenticated, return 401. This path should be hit if no token is provided.
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Debug 2: Dump the authenticated user object
        $staffAuth = Auth::user();

        // If you are still getting the error, uncomment the line below to see what $staffAuth is.
        // dd($staffAuth); // This will stop execution and show you the object

        // Make sure $staffAuth is an instance of StaffAuth before calling staffAccess()
        if (!$staffAuth instanceof \App\Models\StaffAuth) {
            // This means Auth::user() is returning something other than your StaffAuth model.
            return response()->json([
                'message' => 'Authentication misconfiguration: User model mismatch.',
                'expected' => \App\Models\StaffAuth::class,
                'received' => get_class($staffAuth)
            ], 500); // Internal Server Error due to config issue
        }


        // Check if the StaffAuth user has any associated StaffAccess records
        // and if any of those SystemAccess records grant access for the specified module.
        $hasModuleAccess = $staffAuth->staffAccess() // This is the line causing the error
            ->whereHas('systemAccess', function ($query) use ($module) {
                $query->where($module, true);
            })
            ->exists();

        if (!$hasModuleAccess) {
            return response()->json(['message' => "Forbidden: You do not have {$module} access."], 403);
        }

        return $next($request);
    }
}

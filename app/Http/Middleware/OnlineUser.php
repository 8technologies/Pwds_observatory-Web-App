<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class OnlineUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Grab the admin‐guard user once
        $admin = Auth::guard('admin')->user();

        if ($admin) {
            $expiresAt = Carbon::now()->addMinutes(1);
            // Use $admin->id (not Auth::user())
            Cache::put('OnlineUser_'.$admin->id, true, $expiresAt);
            $getUserInfo = User::find($admin->id);
            $getUserInfo->updated_at = now();
            $getUserInfo->save();
        }
        return $next($request);
    }
}

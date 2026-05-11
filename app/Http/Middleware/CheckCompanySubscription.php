<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckCompanySubscription
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // لو مش مسجل دخول
        if (!$user) {
            return redirect()->route('login');
        }

        $company = $user->company;

        // لو مفيش شركة
        if (!$company) {
            return redirect()->route('landing')
                ->with('error', 'No company assigned');
        }

        // لو الشركة مش شغالة أو الاشتراك انتهى
        if (!$company->is_active || $company->is_expired) {
            return redirect()->route('subscription.expired');
        }

        return $next($request);
    }
}

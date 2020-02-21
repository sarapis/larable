<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Layout; 
use Sentinel;

class FrontendAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $layout = Layout::find(1);
        $active = $layout->activate_login_home;
        if ($active == 0) {
            return $next($request);
        }
        if (Sentinel::guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 403);
            } else {
                return redirect()->guest('login');
            }
        }

        return $next($request);
    }
}


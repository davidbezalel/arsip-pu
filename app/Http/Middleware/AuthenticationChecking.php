<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticationChecking
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $whitelist = [
            '/admin/login',
            '/admin/register',
            '/developer/login',
            '/developer/register'
        ];

        $path = explode("/", $request->getPathInfo());

        if (in_array($request->getPathInfo(), $whitelist)) {
            if ($path[1] == 'admin' && Auth::check()) {
                return redirect('/admin/ppk');
            } else if ($path[1] == 'developer' && Auth::guard('developer')->check()) {
                return redirect('/developer');
            }
            return $next($request);
        }

        if ($path[1] == 'admin' && !Auth::check()) {
            return redirect('/admin/login');
        } else if ($path[1] == 'developer' && !Auth::guard('developer')->check()) {
            return redirect('/developer/login');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;

class HttpsProtocol
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (app()->environment('production')) {
            $request->setTrustedProxies( [ $request->getClientIp() ] );
            $server = $request->server();
            if (isset($server['HTTP_X_FORWARDED_PROTO']) and $server['HTTP_X_FORWARDED_PROTO'] == 'http') {
                return redirect()->secure($request->getRequestUri());
            }
        }
        return $next($request);
    }
}

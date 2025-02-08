<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Headers: Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers, x-xsrf-token');
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');

        if (in_array(env('APP_ENV'), ['production'])) {
            header('Access-Control-Allow-Origin: https://mmlogistica.online');
        } else {
            header('Access-Control-Allow-Origin: http://localhost:3030');
        }

        return $next($request);
    }
}

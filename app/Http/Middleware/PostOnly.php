<?php

namespace App\Http\Middleware;

use Closure;

class PostOnly
{
    public function handle($request, Closure $next)
    {
        if ($request->method() !== 'POST') {
            return abort(405);
        }

        return $next($request);
    }
}

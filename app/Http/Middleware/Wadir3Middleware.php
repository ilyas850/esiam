<?php

namespace App\Http\Middleware;

use Closure;

class Wadir3Middleware
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
        $users = $request->user();

        if ($users) {
            if ($users->isWadir3()) {
                return $next($request, $users);
            }
        }

        return redirect('/404');
    }
}

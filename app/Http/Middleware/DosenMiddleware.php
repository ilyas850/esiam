<?php

namespace App\Http\Middleware;

use Closure;

class DosenMiddleware
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
      if ($users->isDosen()) {
        return $next($request, $users);
      }
    }

    return redirect('/404');
  }
}

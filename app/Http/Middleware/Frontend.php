<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
use Redirect;
use SleepingOwl\Admin\Admin;
use Request;
use Response;


class Frontend
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
        if (Sentinel::guest()) return redirect('login');
        if (Sentinel::inRole('admin')) return redirect('admin');
        if (Sentinel::inRole('manager')) return $next($request);
    }
}
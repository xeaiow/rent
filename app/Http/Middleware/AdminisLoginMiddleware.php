<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App\Vendor;

class AdminisLoginMiddleware
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
        if (Session::has('account') && Session::has('token')) {

            $isExist = Vendor::where('account', Session::get('account'))->where('token', Session::get('token'))->count();

            if ($isExist == 1)
            {
                return redirect('pineapple');
            }
            return redirect('pineapple/login');
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\TimeSheet;
use App\Policies\TimeSheetPolicy;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class IsUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if(Gate::denies('viewAll', TimeSheet::class)) {
            return redirect()->route('home_admin');
        }

        return $next($request);
    }
}

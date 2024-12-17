<?php

namespace App\Http\Middleware;

use App\Enum\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class RoleChecks
{
    protected  $ignoreRoutes = ['login', 'login.*', 'profile.*', 'password.*'];
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->getMethod() !== 'GET' && !($request->routeIs($this->ignoreRoutes)|| str($request->path())->contains($this->ignoreRoutes))) {
            if($request->user()->role != UserRole::ADMIN->value) {
                notify()->error('You are not authorized to perform this action');
                return redirect()->route('dashboard');
            }
        }
        return $next($request);
    }
}

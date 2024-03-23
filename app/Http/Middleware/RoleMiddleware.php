<?php
namespace App\Http\Middleware;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class RoleMiddleware
{
    public function handle($request,Closure $next,$role,$guard = null){
        $authGuard = Auth::guard($guard);
        $user = $authGuard->user();
        if($user->role != $role){
            return redirect(RouteServiceProvider::HOME);
        }
        return $next($request);
    }
}

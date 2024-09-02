<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => Response::HTTP_UNAUTHORIZED,
                'message' => 'Please sign in'
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (Auth::user()->role_id != "1"){
            return response()->json([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => "You don't have permission"
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}

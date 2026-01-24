<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{

    public function handle(Request $request, Closure $next,$permission): Response
    {
        $user=$request->user();
        if($user && $user->can($permission))
        {
             return $next($request);
        }
          return response()->json(['message' => 'Forbidden'], 403);
    }
}

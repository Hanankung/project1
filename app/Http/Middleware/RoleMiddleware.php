<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  array|string  $roles  บทบาทที่อนุญาตให้เข้าได้
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (! $user || ! in_array($user->role, $roles)) {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    public function handle(Request $request, Closure $next, $role = 'GM')
    {
        if (!Auth::check())
            return redirect('/');

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 判斷權限等級
        if ($role === 'OM' && !$user->isOM()) {
            abort(403, '只有營運管理員能進入此區域');
        }

        if ($role === 'GM' && !$user->isGM()) {
            abort(403, '權限不足');
        }

        return $next($request);
    }
}

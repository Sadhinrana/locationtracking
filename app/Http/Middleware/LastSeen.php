<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class LastSeen
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $redis = Redis::connection();
        $key = 'last_seen_' . Auth::id();
        $value = (new \DateTime())->format("Y-m-d H:i:s");

        $redis->set($key, $value);
        return $next($request);
    }
}
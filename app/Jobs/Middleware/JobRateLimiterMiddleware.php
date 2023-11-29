<?php

namespace App\Jobs\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Redis;

class JobRateLimiterMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(object $job, Closure $next): void
    {
        Redis::throttle("key")
              ->block(0)
              ->allow(1)
              ->every(5)
              ->then(function() use($job, $next){
                $next($job);
              }, function () use($job){

                $job->release(5);
              });

        
    }
}

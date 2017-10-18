<?php

namespace App\Http\Middleware;

use Closure;

class SetUuid
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
        $uuid       = \Ramsey\Uuid\Uuid::uuid1();
        $uuidString = $uuid->toString();
        $request->merge(['uuid' => $uuidString]);

        return $next($request);
    }
}

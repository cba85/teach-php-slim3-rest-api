<?php

namespace App\Middlewares;

class JsonResponseMiddleware
{
    public function __invoke($request, $response, $next)
    {
        return $next($request, $response->withHeader('Content-Type', 'application/json'));
    }
}
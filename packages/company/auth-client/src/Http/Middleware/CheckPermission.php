<?php

namespace Company\AuthClient\Http\Middleware;

use Closure;
use Company\AuthClient\SSOAuth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function __construct(private readonly SSOAuth $auth) {}

    public function handle(Request $request, Closure $next, string $permission): Response
    {
        abort_unless($this->auth->can($permission), 403);

        return $next($request);
    }
}

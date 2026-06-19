<?php

namespace Company\AuthClient\Http\Middleware;

use Closure;
use Company\AuthClient\SSOClient;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAuth
{
    public function __construct(private readonly SSOClient $client) {}

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->has('sso.user')) {
            $lastCheck = (int) $request->session()->get('sso.validated_at', 0);

            if ($lastCheck >= now()->subSeconds(config('sso.session_revalidate_seconds', 300))->timestamp) {
                return $next($request);
            }

            if ($this->client->sessionIsValid((string) $request->session()->get('sso.session_grant'))) {
                $request->session()->put('sso.validated_at', now()->timestamp);

                return $next($request);
            }

            $request->session()->forget([
                'sso.user',
                'sso.roles',
                'sso.permissions',
                'sso.session_grant',
                'sso.validated_at',
            ]);
        }

        $request->session()->put('sso.intended', $request->fullUrl());
        $authorizeUrl = rtrim(config('sso.auth_url'), '/').'/sso/authorize';

        return redirect()->away($authorizeUrl.'?'.http_build_query([
            'redirect_to' => route('sso.callback'),
        ]));
    }
}

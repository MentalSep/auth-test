<?php

namespace Company\AuthClient;

use Illuminate\Support\Facades\Http;

class SSOClient
{
    public function validate(string $token): array
    {
        return Http::asJson()
            ->timeout(config('sso.request_timeout_seconds', 5))
            ->post(rtrim(config('sso.auth_url'), '/').'/api/sso/validate', [
                'application_id' => config('sso.application_id'),
                'client_secret' => config('sso.client_secret'),
                'token' => $token,
            ])
            ->throw()
            ->json();
    }

    public function sessionIsValid(string $grant): bool
    {
        $response = Http::asJson()
            ->timeout(config('sso.request_timeout_seconds', 5))
            ->post(rtrim(config('sso.auth_url'), '/').'/api/sso/session', [
                'application_id' => config('sso.application_id'),
                'client_secret' => config('sso.client_secret'),
                'session_grant' => $grant,
            ]);

        return $response->successful() && $response->json('valid') === true;
    }
}

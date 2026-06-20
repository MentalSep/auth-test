<?php

namespace Company\AuthClient;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class SSOClient
{
    public function validate(string $token): array
    {
        $response = Http::asJson()
            ->timeout(config('sso.request_timeout_seconds', 5))
            ->post(rtrim(config('sso.auth_url'), '/').'/api/sso/validate', [
                'client_secret' => config('sso.client_secret'),
                'token' => $token,
            ]);

        if ($response->failed()) {
            throw new RuntimeException(
                'SSO validation failed (HTTP '.$response->status().'): '.
                $response->json('message', 'Unknown error')
            );
        }

        return $response->json();
    }

    public function sessionIsValid(string $grant): bool
    {
        $response = Http::asJson()
            ->timeout(config('sso.request_timeout_seconds', 5))
            ->post(rtrim(config('sso.auth_url'), '/').'/api/sso/session', [
                'client_secret' => config('sso.client_secret'),
                'session_grant' => $grant,
            ]);

        return $response->successful() && $response->json('valid') === true;
    }
}

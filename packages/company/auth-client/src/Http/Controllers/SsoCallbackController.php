<?php

namespace Company\AuthClient\Http\Controllers;

use Company\AuthClient\SSOClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RuntimeException;

class SsoCallbackController
{
    public function __invoke(Request $request, SSOClient $client): RedirectResponse
    {
        $token = $request->validate(['token' => ['required', 'string']])['token'];

        try {
            $profile = $client->validate($token);
        } catch (RuntimeException $e) {
            report($e);

            return redirect('/')->with('sso_error', $e->getMessage());
        }

        $request->session()->put([
            'sso.user' => $profile['user'],
            'sso.roles' => $profile['roles'] ?? [],
            'sso.permissions' => $profile['permissions'] ?? [],
            'sso.session_grant' => $profile['session_grant'],
            'sso.validated_at' => now()->timestamp,
        ]);
        $request->session()->regenerate();

        return redirect()->to($request->session()->pull('sso.intended', '/'));
    }
}

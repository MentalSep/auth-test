<?php

namespace Company\AuthClient\Http\Controllers;

use Company\AuthClient\SSOClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SsoCallbackController
{
    public function __invoke(Request $request, SSOClient $client): RedirectResponse
    {
        $token = $request->validate(['token' => ['required', 'string']])['token'];
        $profile = $client->validate($token);

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

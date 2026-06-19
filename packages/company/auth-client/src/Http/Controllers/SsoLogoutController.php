<?php

namespace Company\AuthClient\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SsoLogoutController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $request->session()->forget([
            'sso.user',
            'sso.roles',
            'sso.permissions',
            'sso.session_grant',
            'sso.validated_at',
            'sso.intended',
        ]);

        return redirect()->away(rtrim(config('sso.auth_url'), '/').'/sso/logout?'.http_build_query([
            'redirect_to' => config('app.url'),
        ]));
    }
}

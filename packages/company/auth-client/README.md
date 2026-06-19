# company/auth-client

Laravel client for the central authentication server (SAC).

## Install

Add the private Composer repository configured by your organization, then run:

```bash
composer require company/auth-client:^1.0
php artisan vendor:publish --tag=sso-config
```

The package registers its service provider, callback route, logout route, and
middleware aliases automatically.

## Configure

Register the client application in the SAC admin panel and copy its ID and
secret into the client application's `.env`:

```env
SSO_AUTH_URL=https://auth.company.internal
SSO_APPLICATION_ID=1
SSO_CLIENT_SECRET=replace-with-the-generated-secret
SSO_CALLBACK_PATH=/auth/callback
SSO_LOGOUT_PATH=/auth/logout-central
SSO_SESSION_REVALIDATE_SECONDS=300
SSO_REQUEST_TIMEOUT_SECONDS=5
```

The callback URL registered in the SAC must match:

```text
https://your-app.company.internal/auth/callback
```

The client application must use HTTPS and a normal Laravel session driver.

## Protect routes

```php
use Illuminate\Support\Facades\Route;

Route::middleware('auth.central')->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'));
});

Route::middleware(['auth.central', 'check.permission:report:view'])
    ->get('/reports', fn () => view('reports'));
```

`auth.central` redirects unauthenticated users to the SAC. After callback, it
stores the user profile, roles, permissions, and session grant in the Laravel
session. The central session is revalidated every five minutes.

## Read the authenticated user

```php
use Company\AuthClient\Facades\SSOAuth;

$user = SSOAuth::user();
$roles = SSOAuth::roles();
$permissions = SSOAuth::permissions();

if (SSOAuth::can('report:view')) {
    // Authorized.
}
```

## Single Sign-Out

```blade
<form method="POST" action="{{ route('sso.client.logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>
```

This clears local SSO data and redirects through the SAC logout endpoint.

## Integration check

1. Open a protected client route while logged out.
2. Confirm redirection to `auth.company.internal`.
3. Log in and confirm return to the original client route.
4. Confirm permission-protected routes return `403` without the permission.
5. Log out and confirm the protected route requires authentication again.

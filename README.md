# SSO Test Client

Minimal Laravel 13 client for `company/auth-client`.

## Local setup

```powershell
Copy-Item .env.example .env
composer install
php artisan key:generate
php artisan serve
```

Set the SSO values in `.env`, then open `http://localhost:8000`.

## Vercel

Import this repository into Vercel and set:

```env
APP_NAME=SSO Test Client
APP_ENV=production
APP_KEY=base64:generated-value
APP_DEBUG=false
APP_URL=https://auth-test-olive.vercel.app
LOG_CHANNEL=stderr
CACHE_STORE=array
SESSION_DRIVER=cookie
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
SSO_AUTH_URL=https://your-auth-domain
SSO_CLIENT_SECRET=your-client-secret
SSO_CALLBACK_PATH=/auth/callback
SSO_LOGOUT_PATH=/auth/logout-central
SSO_SESSION_REVALIDATE_SECONDS=300
SSO_REQUEST_TIMEOUT_SECONDS=5
```

Generate `APP_KEY` locally with `php artisan key:generate --show`.

Whitelist this callback URL in the auth project:

```text
https://auth-test-olive.vercel.app/auth/callback
```

If the Vercel project uses another production or custom domain, set `APP_URL`
to that exact origin and whitelist `<APP_URL>/auth/callback` instead.

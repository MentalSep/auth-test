<?php

return [
    'auth_url' => env('SSO_AUTH_URL', 'https://auth.company.internal'),
    'application_id' => env('SSO_APPLICATION_ID'),
    'client_secret' => env('SSO_CLIENT_SECRET'),
    'callback_path' => env('SSO_CALLBACK_PATH', '/auth/callback'),
    'logout_path' => env('SSO_LOGOUT_PATH', '/auth/logout-central'),
    'session_revalidate_seconds' => (int) env('SSO_SESSION_REVALIDATE_SECONDS', 300),
    'request_timeout_seconds' => (int) env('SSO_REQUEST_TIMEOUT_SECONDS', 5),
];

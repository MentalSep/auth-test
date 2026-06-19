<?php

namespace Company\AuthClient;

class SSOAuth
{
    public function user(): ?array
    {
        return session('sso.user');
    }

    public function roles(): array
    {
        return session('sso.roles', []);
    }

    public function permissions(): array
    {
        return session('sso.permissions', []);
    }

    public function can(string $permission): bool
    {
        return in_array($permission, $this->permissions(), true);
    }
}

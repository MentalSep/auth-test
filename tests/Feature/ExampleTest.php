<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_public_and_protected_pages(): void
    {
        $this->get('/')->assertOk()->assertSee('This page is public');

        $this->withHeader('X-Forwarded-Proto', 'https')
            ->get('/dashboard')
            ->assertRedirect('https://auth.example.com/sso/authorize?redirect_to=https%3A%2F%2Flocalhost%2Fauth%2Fcallback');

        $this->withSession([
            'sso.user' => ['name' => 'Test User'],
            'sso.roles' => ['admin'],
            'sso.permissions' => ['users:view'],
            'sso.validated_at' => now()->timestamp,
        ])->get('/dashboard')
            ->assertOk()
            ->assertSeeText(['Test User', 'admin', 'users:view']);
    }
}

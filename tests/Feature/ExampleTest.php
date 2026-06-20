<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_public_and_protected_pages(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSeeText('The landing page is public');

        $this->withHeader('X-Forwarded-Proto', 'https')
            ->get('/projects')
            ->assertRedirect('https://auth.example.com/sso/authorize?redirect_to=https%3A%2F%2Flocalhost%2Fauth%2Fcallback');

        $session = [
            'sso.user' => ['name' => 'Test User'],
            'sso.roles' => ['admin'],
            'sso.permissions' => ['users:view'],
            'sso.validated_at' => now()->timestamp,
        ];

        $this->withSession($session)->get('/dashboard')
            ->assertOk()->assertSeeText(['Welcome back, Test User', 'Active projects']);
        $this->withSession($session)->get('/projects')
            ->assertOk()->assertSeeText('Client Portal');
        $this->withSession($session)->get('/reports')
            ->assertOk()->assertSeeText('Weekly delivery');
        $this->withSession($session)->get('/profile')
            ->assertOk()->assertSeeText(['Test User', 'admin', 'users:view', 'Central auth account']);
    }
}

<?php

use Company\AuthClient\Facades\SSOAuth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('app', [
    'section' => 'home',
    'user' => SSOAuth::user(),
    'roles' => SSOAuth::roles(),
    'permissions' => SSOAuth::permissions(),
]))->name('home');

Route::middleware('auth.central')->get('/{section}', function (string $section) {
    return view('app', [
        'section' => $section,
        'user' => SSOAuth::user(),
        'roles' => SSOAuth::roles(),
        'permissions' => SSOAuth::permissions(),
    ]);
})->whereIn('section', ['dashboard', 'projects', 'reports', 'profile'])->name('app.page');

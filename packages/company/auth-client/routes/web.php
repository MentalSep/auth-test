<?php

use Company\AuthClient\Http\Controllers\SsoCallbackController;
use Company\AuthClient\Http\Controllers\SsoLogoutController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->get(
    config('sso.callback_path', '/auth/callback'),
    SsoCallbackController::class,
)->name('sso.callback');

Route::middleware('web')->post(
    config('sso.logout_path', '/auth/logout-central'),
    SsoLogoutController::class,
)->name('sso.client.logout');

<?php

namespace Company\AuthClient\Facades;

use Illuminate\Support\Facades\Facade;

class SSOAuth extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'sso-auth';
    }
}

<?php

namespace DutchCodingCompany\FilamentDeveloperLogins\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin \DutchCodingCompany\FilamentDeveloperLogins\FilamentDevelopersLogin
 */
class FilamentDevelopersLogin extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'filament-developers-login';
    }
}

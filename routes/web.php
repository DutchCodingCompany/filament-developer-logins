<?php

use DutchCodingCompany\FilamentDeveloperLogins\Http\Controllers\DeveloperLoginsController;
use Illuminate\Support\Facades\Route;

Route::post('filament-developer-logins', [DeveloperLoginsController::class, 'loginAs'])
    ->middleware(['web'])
    ->name('filament-developer-logins.login-as');



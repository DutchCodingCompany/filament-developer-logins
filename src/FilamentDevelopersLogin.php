<?php

namespace DutchCodingCompany\FilamentDeveloperLogins;

use DutchCodingCompany\FilamentDeveloperLogins\Exceptions\ImplementationException;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Livewire\Features\SupportRedirects\Redirector;

class FilamentDevelopersLogin
{
    public function login(Panel $panel, FilamentDeveloperLoginsPlugin $plugin, string $credentials): RedirectResponse | Redirector
    {
        if (! in_array($credentials, $plugin->getUsers())) {
            throw new ImplementationException('The user is not found in the defined users, please check the configuration of the plugin.');
        }

        if ($panel->auth()->check()) {
            $panel->auth()->logout();

            session()->forget('password_hash_'.$panel->getAuthGuard());
        }

        /** @var ?\Illuminate\Contracts\Auth\Authenticatable $model */
        $model = (new ($plugin->getModelClass()))
            ->where($plugin->getColumn(), $credentials)
            ->first();

        if (! $model) {
            throw ValidationException::withMessages([
                'developer-logins-failed' => __('filament-developer-logins::auth.messages.failed_not_found'),
            ]);
        }

        $panel->auth()->login($model);

        if (
            ($model instanceof FilamentUser) &&
            (! $model->canAccessPanel($panel))
        ) {
            $panel->auth()->logout();

            throw ValidationException::withMessages([
                'developer-logins-failed' => __('filament-developer-logins::auth.messages.failed'),
            ]);
        }

        session()->regenerate();

        return redirect()
            ->to(Redirect::getIntendedUrl() ?? $plugin->getRedirectTo() ?? $panel->getUrl());
    }
}

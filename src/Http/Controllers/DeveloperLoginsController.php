<?php

namespace DutchCodingCompany\FilamentDeveloperLogins\Http\Controllers;

use DutchCodingCompany\FilamentDeveloperLogins\Exceptions\ImplementationException;
use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;
use DutchCodingCompany\FilamentDeveloperLogins\Http\Requests\LoginAsRequest;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class DeveloperLoginsController extends Controller
{
    protected Panel $panel;

    protected FilamentDeveloperLoginsPlugin $plugin;

    /**
     * @throws ImplementationException
     */
    public function loginAs(LoginAsRequest $request): RedirectResponse
    {
        $this->initiate($request);

        $credentials = $request->validated('credentials');
        if (! in_array($credentials, $this->plugin->getUsers())) {
            throw new ImplementationException('The user is not found in the defined users, please check the configuration of the plugin.');
        }

        $model = (new ($this->plugin->getModelClass()))
            ->where($this->plugin->getColumn(), $credentials)->firstOrFail();

        Filament::auth()->login($model);

        if (
            ($model instanceof FilamentUser) &&
            (! $model->canAccessPanel($this->panel))
        ) {
            Auth::logout();

            throw ValidationException::withMessages([
                'developer-logins-failed' => __('filament-developer-logins::auth.messages.failed'),
            ]);
        }

        session()->regenerate();

        return redirect()
            ->to(
                $this->plugin->getRedirectTo() ?? $this->panel->getUrl()
            );
    }

    protected function initiate(LoginAsRequest $request): void
    {
        $this->panel = Filament::getPanel($request->validated('panel_id'));

        $this->plugin = FilamentDeveloperLoginsPlugin::getById($request->validated('panel_id'));
    }
}

<?php

namespace DutchCodingCompany\FilamentDeveloperLogins\Http\Controllers;

use DutchCodingCompany\FilamentDeveloperLogins\Exceptions\ImplementationException;
use DutchCodingCompany\FilamentDeveloperLogins\Facades\FilamentDevelopersLogin;
use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;
use DutchCodingCompany\FilamentDeveloperLogins\Http\Requests\LoginAsRequest;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Livewire\Features\SupportRedirects\Redirector;

class DeveloperLoginsController extends Controller
{
    /**
     * @throws ImplementationException
     */
    public function loginAs(LoginAsRequest $request): RedirectResponse | Redirector
    {
        $panel = Filament::getPanel($request->validated('panel_id'));
        $plugin = FilamentDeveloperLoginsPlugin::getById($request->validated('panel_id'));
        $credentials = $request->validated('credentials');

        return FilamentDevelopersLogin::login($panel, $plugin, $credentials);
    }
}

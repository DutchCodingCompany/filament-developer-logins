<?php

namespace DutchCodingCompany\FilamentDeveloperLogins\Livewire;

use DutchCodingCompany\FilamentDeveloperLogins\Facades\FilamentDevelopersLogin;
use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;

class MenuLogins extends Component
{
    protected FilamentDeveloperLoginsPlugin $plugin;

    public function __construct()
    {
        $this->plugin = FilamentDeveloperLoginsPlugin::current();
    }

    public function render(): View
    {
        return view('filament-developer-logins::livewire.menu-logins', [
            'users' => $this->plugin->getUsers(),
            'current' => $this->getCurrentUser(),
        ]);
    }

    protected function getCurrentUser(): string
    {
        return Filament::auth()->user()->{$this->plugin->getColumn()};
    }

    public function loginAs(string $credentials): RedirectResponse | Redirector
    {
        if (! $this->plugin->getEnabled() || ! $this->plugin->getSwitchable()) {
            abort(403);
        }

        return FilamentDevelopersLogin::login(Filament::getPanel(), $this->plugin, $credentials, true);
    }
}

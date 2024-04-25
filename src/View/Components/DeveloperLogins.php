<?php

namespace DutchCodingCompany\FilamentDeveloperLogins\View\Components;

use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;
use Illuminate\View\Component;
use Illuminate\View\View;

class DeveloperLogins extends Component
{
    protected FilamentDeveloperLoginsPlugin $plugin;

    public function __construct()
    {
        $this->plugin = FilamentDeveloperLoginsPlugin::current();
    }

    public function render(): View
    {
        return view('filament-developer-logins::components.developer-logins', [
            'users' => $this->plugin->getUsers(),
        ]);
    }
}

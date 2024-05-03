<?php

namespace DutchCodingCompany\FilamentDeveloperLogins;

use DutchCodingCompany\FilamentDeveloperLogins\Livewire\MenuLogins;
use DutchCodingCompany\FilamentDeveloperLogins\View\Components\DeveloperLogins;
use Filament\Facades\Filament;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentDeveloperLoginsServiceProvider extends PackageServiceProvider
{
    use EvaluatesClosures;

    public static string $viewNamespace = 'skeleton';

    public function configurePackage(Package $package): void
    {
        $package->name('filament-developer-logins')
            ->hasViews()
            ->hasRoute('web')
            ->hasTranslations();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(FilamentDevelopersLogin::class);
        $this->app->bind('filament-developers-login', FilamentDevelopersLogin::class);
    }

    public function packageBooted(): void
    {
        Blade::componentNamespace('DutchCodingCompany\FilamentDeveloperLogins\View\Components', 'filament-developer-logins');
        Blade::component('developer-logins', DeveloperLogins::class);
        Livewire::component('menu-logins', MenuLogins::class);

        $this->registerRenderHooks();
    }

    protected static function registerRenderHooks(): void
    {
        $panel = Filament::getCurrentPanel();
        if (is_null($panel) || ! $panel->hasPlugin('filament-developer-logins')) {
            return;
        }

        /** @var FilamentDeveloperLoginsPlugin $plugin */
        $plugin = $panel->getPlugin('filament-developer-logins');

        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
            static function () use ($plugin) : ?string {
                if (! $plugin->getEnabled()) {
                    return null;
                }

                return Blade::render('<x-filament-developer-logins::developer-logins />');
            },
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_AFTER,
            static function () use ($plugin) : ?string {
                if (! $plugin->getEnabled() || ! $plugin->getSwitchable()) {
                    return null;
                }

                return Blade::render('@livewire(\'menu-logins\')');
            },
        );
    }
}

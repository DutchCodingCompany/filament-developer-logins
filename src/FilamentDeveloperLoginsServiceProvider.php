<?php

namespace DutchCodingCompany\FilamentDeveloperLogins;

use DutchCodingCompany\FilamentDeveloperLogins\View\Components\DeveloperLogins;
use Filament\Facades\Filament;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
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

    public function packageBooted(): void
    {
        Blade::componentNamespace('DutchCodingCompany\FilamentDeveloperLogins\View\Components', 'filament-developer-logins');
        Blade::component('developer-logins', DeveloperLogins::class);

        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
            static function (): ?string {
                $panel = Filament::getCurrentPanel();
                if (! $panel->hasPlugin('filament-developer-logins')) {
                    return null;
                }

                /** @var FilamentDeveloperLoginsPlugin $plugin */
                $plugin = $panel->getPlugin('filament-developer-logins');
                if (is_bool($plugin->getEnabled())
                    ? ! $plugin->getEnabled()
                    : ! call_user_func($plugin->getEnabled())
                ) {
                    return null;
                }

                return Blade::render('<x-filament-developer-logins::developer-logins />');
            },
        );
    }
}

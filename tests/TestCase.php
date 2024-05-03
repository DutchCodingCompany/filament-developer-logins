<?php

namespace DutchCodingCompany\FilamentDeveloperLogins\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;
use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsServiceProvider;
use DutchCodingCompany\FilamentDeveloperLogins\Http\Controllers\DeveloperLoginsController;
use DutchCodingCompany\FilamentDeveloperLogins\Tests\Fixtures\TestUser;
use Filament\Actions\ActionsServiceProvider;
use Filament\Facades\Filament;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\Support\SupportServiceProvider;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Encryption\Encrypter;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;

class TestCase extends Orchestra
{
    protected string $panelName = 'test-panel';

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (
                string $modelName,
            ) => 'DutchCodingCompany\\FilamentDeveloperLogins\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function registerTestPanel(): void
    {
        Filament::registerPanel(
            fn (): Panel => Panel::make()
                ->default()
                ->darkMode(false)
                ->id($this->panelName)
                ->path($this->panelName)
                ->login()
                ->pages([
                    Dashboard::class,
                ])
                ->plugins([
                    FilamentDeveloperLoginsPlugin::make()
                        ->enabled()
                        ->users([
                            'Administrator' => 'developer@dutchcodingcompany.com',
                        ])
                        ->modelClass(TestUser::class),
                ]),
        );
    }

    protected function defineRoutes($router): void
    {
        $router->post('filament-developer-logins', [DeveloperLoginsController::class, 'loginAs'])
            ->middleware(['web'])
            ->name('filament-developer-logins.login-as');
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/Fixtures');
    }

    protected function getEnvironmentSetUp($app): void
    {
        config()->set('app.key', 'base64:'.base64_encode(
            Encrypter::generateKey('AES-256-CBC')
        ));
    }

    protected function getPackageProviders($app): array
    {
        $this->registerTestPanel();

        return [
            FilamentServiceProvider::class,
            BladeCaptureDirectiveServiceProvider::class,
            WidgetsServiceProvider::class,
            SupportServiceProvider::class,
            FormsServiceProvider::class,
            ActionsServiceProvider::class,
            NotificationsServiceProvider::class,
            FilamentDeveloperLoginsServiceProvider::class,
            LivewireServiceProvider::class,
        ];
    }
}

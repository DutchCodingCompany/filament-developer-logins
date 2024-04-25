<?php

namespace DutchCodingCompany\FilamentDeveloperLogins;

use App\Models\User;
use Closure;
use DutchCodingCompany\FilamentDeveloperLogins\Exceptions\ImplementationException;
use Filament\Contracts\Plugin;
use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Contracts\Auth\Authenticatable;

class FilamentDeveloperLoginsPlugin implements Plugin
{
    public Closure | bool $enabled = false;

    /**
     * @var array<string, string>
     */
    public array $users = [];

    public ?string $redirectTo = null;

    public ?string $panelId = null;

    public string $column = 'email';

    public string $modelClass = User::class;

    public function getId(): string
    {
        return 'filament-developer-logins';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function register(Panel $panel): void
    {
        $this->panelId = $panel->getId();
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function current(): static
    {
        if (Filament::getCurrentPanel()?->hasPlugin('filament-developer-logins')) {
            /** @var static $plugin */
            $plugin = Filament::getCurrentPanel()->getPlugin('filament-developer-logins');

            return $plugin;
        }

        throw new ImplementationException('No current panel found with filament-developer-logins plugin.');
    }

    public static function getById(string $panelId): static
    {
        if (Filament::getPanel($panelId)->hasPlugin('filament-developer-logins')) {
            /** @var static $plugin */
            $plugin = Filament::getPanel($panelId)->getPlugin('filament-developer-logins');

            return $plugin;
        }

        throw new ImplementationException('No panel found with filament-developer-logins plugin.');
    }

    public function enabled(Closure | bool $value = true): static
    {
        $this->enabled = $value;

        return $this;
    }

    public function getEnabled(): Closure | bool
    {
        return $this->enabled;
    }

    /**
     * @param array<string, string> $users
     *
     * @return $this
     */
    public function users(array $users): static
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    public function redirectTo(string $routeName): static
    {
        $this->redirectTo = $routeName;

        return $this;
    }

    public function getRedirectTo(): string | null
    {
        return $this->redirectTo;
    }

    public function column(string $column): static
    {
        $this->column = $column;

        return $this;
    }

    public function getColumn(): string
    {
        return $this->column;
    }

    public function modelClass(string $modelClass): static
    {
        if (! is_a($modelClass, Authenticatable::class, true)) {
            throw new ImplementationException('The model class must implement the "\Illuminate\Contracts\Auth\Authenticatable" interface.');
        }

        $this->modelClass = $modelClass;

        return $this;
    }

    public function getModelClass(): string
    {
        return $this->modelClass;
    }
}

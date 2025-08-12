<?php

namespace DutchCodingCompany\FilamentDeveloperLogins;

use App\Models\User;
use Closure;
use DutchCodingCompany\FilamentDeveloperLogins\Exceptions\ImplementationException;
use Filament\Contracts\Plugin;
use Filament\Facades\Filament;
use Filament\Schemas\Concerns\HasColumns;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;

class FilamentDeveloperLoginsPlugin implements Plugin
{
    use EvaluatesClosures, HasColumns;

    /**
     * @var class-string<\Illuminate\Database\Eloquent\Model&\Illuminate\Contracts\Auth\Authenticatable>
     */
    public string $modelClass = '';

    public Closure | bool $enabled = false;

    public Closure | bool $switchable = true;

    /**
     * @var array<string, string>
     */
    public Closure | array $users = [];

    public Closure | string | null $redirectTo = null;

    public ?string $panelId = null;

    public string $column = 'email';

    public function __construct()
    {
        $this->modelClass = config('auth.providers.users.model') ?? User::class;
    }

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

    public function getEnabled(): bool
    {
        return $this->evaluate($this->enabled);
    }

    public function switchable(Closure | bool $value): static
    {
        $this->switchable = $value;

        return $this;
    }

    public function getSwitchable(): bool
    {
        return $this->evaluate($this->switchable);
    }

    /**
     * @param Closure | array<string, string> $users
     *
     * @return $this
     */
    public function users(Closure | array $users): static
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function getUsers(): array
    {
        return $this->evaluate($this->users);
    }

    public function redirectTo(Closure | string $redirectTo): static
    {
        $this->redirectTo = $redirectTo;

        return $this;
    }

    public function getRedirectTo(): string | null
    {
        return $this->evaluate($this->redirectTo);
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

    /**
     * @param class-string<\Illuminate\Database\Eloquent\Model&\Illuminate\Contracts\Auth\Authenticatable> $modelClass
     */
    public function modelClass(string $modelClass): static
    {
        $this->modelClass = $modelClass;

        return $this;
    }

    /**
     * @return class-string<\Illuminate\Database\Eloquent\Model&\Illuminate\Contracts\Auth\Authenticatable>
     */
    public function getModelClass(): string
    {
        return $this->modelClass;
    }
}

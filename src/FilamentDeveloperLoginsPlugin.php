<?php

namespace DutchCodingCompany\FilamentDeveloperLogins;

use App\Models\User;
use Closure;
use DutchCodingCompany\FilamentDeveloperLogins\Exceptions\ImplementationException;
use Filament\Contracts\Plugin;
use Filament\Facades\Filament;
use Filament\Forms\Concerns\HasColumns;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Contracts\Auth\Authenticatable;

class FilamentDeveloperLoginsPlugin implements Plugin
{
    use EvaluatesClosures, HasColumns;

    public Closure | bool $enabled = false;

    public Closure | bool $switchable = true;

    /**
     * @var array<string, string>
     */
    public CLosure | array $users = [];

    public Closure | string | null $redirectTo = null;

    public ?string $panelId = null;

    public string $column = 'email';

    /**
     * @var class-string<\Illuminate\Database\Eloquent\Model&\Illuminate\Contracts\Auth\Authenticatable>
     */
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
     * @param CLosure | array<string, string> $users
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
        if (! is_a($modelClass, Authenticatable::class, true)) {
            throw new ImplementationException('The model class must implement the "\Illuminate\Contracts\Auth\Authenticatable" interface.');
        }

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

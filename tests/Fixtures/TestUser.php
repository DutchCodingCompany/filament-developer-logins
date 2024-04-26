<?php

namespace DutchCodingCompany\FilamentDeveloperLogins\Tests\Fixtures;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestUser extends Model implements Authenticatable, FilamentUser
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'is_admin',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    public function getAuthIdentifierName(): string
    {
        return 'test-user-auth-identifier-name';
    }

    public function getAuthIdentifier(): string
    {
        return 'test-user-auth-identifier';
    }

    public function getAuthPassword(): string
    {
        return 'test-user-auth-password';
    }

    public function getRememberToken(): string
    {
        return 'test-user-remember-token';
    }

    public function setRememberToken($value): void
    {
        //
    }

    public function getRememberTokenName(): string
    {
        return 'test-user-remember-token-name';
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin;
    }

    public function getAuthPasswordName(): string
    {
        return 'test-user-auth-password-name';
    }
}

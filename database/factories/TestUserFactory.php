<?php

namespace DutchCodingCompany\FilamentDeveloperLogins\Database\Factories;

use DutchCodingCompany\FilamentDeveloperLogins\Tests\Fixtures\TestUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestUserFactory extends Factory
{
    protected $model = TestUser::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'is_admin' => true,
        ];
    }
}

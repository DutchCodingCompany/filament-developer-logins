<?php

namespace DutchCodingCompany\FilamentDeveloperLogins\Tests\Feature;

use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;
use DutchCodingCompany\FilamentDeveloperLogins\Tests\Fixtures\TestUser;
use DutchCodingCompany\FilamentDeveloperLogins\Tests\TestCase;
use Filament\Facades\Filament;

final class DeveloperLoginsControllerTest extends TestCase
{
    public function test_user_can_login_with_defined_users(): void
    {
        $user = TestUser::factory()->create([
            'email' => 'developer@dutchcodingcompany.com',
        ]);

        $this->post(route('filament-developer-logins.login-as'), [
            'credentials' => $user->email,
            'panel_id' => 'test-panel',
        ])->assertRedirect();

        $this->assertAuthenticated();
        $this->assertEquals($user->toArray(), Filament::auth()->user()->toArray());
    }

    public function test_if_user_is_not_allowed_to_login_on_the_panel_the_user_is_logged_out(): void
    {
        $user = TestUser::factory()->create([
            'email' => 'developer@dutchcodingcompany.com',
            'is_admin' => false,
        ]);

        $this->post(route('filament-developer-logins.login-as'), [
            'credentials' => $user->email,
            'panel_id' => 'test-panel',
        ])->assertRedirect();

        $this->assertFalse(Filament::auth()->check());
    }

    public function test_an_exception_is_thrown_when_the_email_is_not_registered(): void
    {
        $user = TestUser::factory()->create();

        $this->post(route('filament-developer-logins.login-as'), [
            'credentials' => $user->email,
            'panel_id' => 'test-panel',
        ])->assertStatus(500);
    }

    public function test_403_is_returned_when_plugin_is_disabled(): void
    {
        FilamentDeveloperLoginsPlugin::current()
            ->enabled(false);

        $user = TestUser::factory()->create([
            'email' => 'developer@dutchcodingcompany.com',
        ]);

        $this->post(route('filament-developer-logins.login-as'), [
            'credentials' => $user->email,
            'panel_id' => 'test-panel',
        ])->assertForbidden();
    }
}

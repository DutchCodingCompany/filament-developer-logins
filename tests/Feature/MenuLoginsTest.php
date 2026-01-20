<?php

namespace DutchCodingCompany\FilamentDeveloperLogins\Tests\Feature;

use DutchCodingCompany\FilamentDeveloperLogins\Exceptions\ImplementationException;
use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;
use DutchCodingCompany\FilamentDeveloperLogins\Livewire\MenuLogins;
use DutchCodingCompany\FilamentDeveloperLogins\Tests\Fixtures\TestUser;
use DutchCodingCompany\FilamentDeveloperLogins\Tests\TestCase;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Livewire\Livewire;

final class MenuLoginsTest extends TestCase
{
    public function test_component_is_rendered(): void
    {
        $user = TestUser::factory()->create();

        $this->actingAs($user)
            ->get(Dashboard::getUrl())
            ->assertSeeLivewire(MenuLogins::class);
    }

    public function test_component_is_not_rendered_when_switchable_is_false(): void
    {
        FilamentDeveloperLoginsPlugin::current()
            ->switchable(false);

        $user = TestUser::factory()->create();

        $this->actingAs($user)
            ->get(Dashboard::getUrl())
            ->assertDontSeeLivewire(MenuLogins::class);
    }

    public function test_component_is_not_rendered_when_plugin_is_not_enabled(): void
    {
        FilamentDeveloperLoginsPlugin::current()
            ->enabled(false);

        $user = TestUser::factory()->create();

        $this->actingAs($user)
            ->get(Dashboard::getUrl())
            ->assertDontSeeLivewire(MenuLogins::class);
    }

    public function test_livewire_displays_to_correct_amount_of_users(): void
    {
        Livewire::actingAs(TestUser::factory()->create())
            ->test(MenuLogins::class)
            ->assertViewHas('users', function (array $users) {
                return count($users) === 1;
            });
    }

    public function test_forbidden_is_returned_when_enabled_or_switchable_is_false(): void
    {
        $authenticatedUser = TestUser::factory()->create();

        TestUser::factory()->create([
            'email' => 'developer@dutchcodingcompany.com',
            'is_admin' => false,
        ]);

        FilamentDeveloperLoginsPlugin::current()
            ->enabled(false);

        Livewire::actingAs($authenticatedUser)
            ->test(MenuLogins::class)
            ->call('loginAs', 'developer@dutchcodingcompany.com')
            ->assertForbidden();

        FilamentDeveloperLoginsPlugin::current()
            ->enabled()
            ->switchable(false);

        Livewire::actingAs($authenticatedUser)
            ->test(MenuLogins::class)
            ->call('loginAs', 'developer@dutchcodingcompany.com')
            ->assertForbidden();
    }

    public function test_component_is_only_rendered_in_panel_with_plugin(): void
    {
        $user = TestUser::factory()->create();

        $this->actingAs($user)
            ->get(Dashboard::getUrl(panel: $this->panelName))
            ->assertSuccessful()
            ->assertSeeLivewire(MenuLogins::class);

        $this->expectException(ImplementationException::class);

        $this->actingAs($user)
            ->get(Dashboard::getUrl(panel: $this->panelName.'-other'))
            ->assertSuccessful()
            ->assertDontSeeLivewire(MenuLogins::class);
    }
}

@if (filled($users))
    <div>
        <x-filament::dropdown>
            <x-slot name="trigger">
                <x-filament::button icon="heroicon-o-user" color="gray" outlined="false">
                    {{ __('filament-developer-logins::auth.switch-to') }}
                </x-filament::button>
            </x-slot>

            <x-filament::dropdown.list>
                @foreach ($users as $label => $credentials)
                    <x-filament::dropdown.list.item
                        wire:click="loginAs('{{ $credentials }}')"
                        color="{{ $credentials === $current ? 'primary' : 'gray' }}"
                    >
                        {{ "$label ($credentials)" }}
                    </x-filament::dropdown.list.item>
                @endforeach
            </x-filament::dropdown.list>
        </x-filament::dropdown>
    </div>
@endif

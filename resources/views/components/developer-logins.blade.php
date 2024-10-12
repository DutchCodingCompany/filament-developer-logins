@if(count($users) > 0)
    <div class="flex flex-col gap-y-6">

        <div class="flex items-center justify-center text-center gap-x-4">
            <div class="border-t border-gray-200 grow h-px"></div>
            <p class="font-medium text-gray-500 dark:text-gray-100 shrink">
                {{ __('filament-developer-logins::auth.login-as') }}
            </p>
            <div class="border-t border-gray-200 grow h-px"></div>
        </div>

        @if ($errors->has('developer-logins-failed'))
            <div class="justify-center text-center">
                <p class="fi-fo-field-wrp-error-message text-danger-600 dark:text-danger-400">
                    {{ $errors->first('developer-logins-failed') }}
                </p>
            </div>
        @endif

        <x-filament::grid
                :default="$columns['default'] ?? 1"
                :sm="$columns['sm'] ?? null"
                :md="$columns['md'] ?? null"
                :lg="$columns['lg'] ?? ($columns ? (is_array($columns) ? null : $columns) : 2)"
                :xl="$columns['xl'] ?? null"
                :two-xl="$columns['2xl'] ?? null"
                :attributes="\Filament\Support\prepare_inherited_attributes($attributes)->class('fi-wi gap-4')"
        >
            @foreach ($users as $label => $credentials)
                <form action="{{ route('filament-developer-logins.login-as') }}" method="POST">
                    @csrf

                    <input type="hidden" name="panel_id" value="{{ \Filament\Facades\Filament::getId() }}">
                    <input type="hidden" name="credentials" value="{{ $credentials }}">

                    <x-filament::button class="w-full" color="gray" outlined="true" type="submit">
                        {{ "$label ($credentials)" }}
                    </x-filament::button>
                </form>
            @endforeach
        </x-filament::grid>
    </div>
@endif
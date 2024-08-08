<div class="flex flex-col gap-y-6">
    <div class="relative flex items-center justify-center text-center">
        <div class="absolute border-t border-gray-200 w-full h-px"></div>
        <p class="inline-block relative bg-white text-sm p-2 rounded-full font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-100">
            {{ __('filament-developer-logins::auth.login-as') }}
        </p>
    </div>

    @if ($errors->has('developer-logins-failed'))
        <div class="justify-center text-center">
            <p class="fi-fo-field-wrp-error-message text-danger-600 dark:text-danger-400">
                {{ $errors->first('developer-logins-failed') }}
            </p>
        </div>
    @endif

    <div class="flex flex-col gap-4">
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
    </div>
</div>


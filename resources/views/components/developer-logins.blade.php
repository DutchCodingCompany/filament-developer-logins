@if(count($users) > 0)
    <div class="fi-simple-header">
        <p>
            {{ __('filament-developer-logins::auth.login-as') }}
        </p>

        @if ($errors->has('developer-logins-failed'))
            <p style="color: oklch(0.577 0.245 27.325);">
                {{ $errors->first('developer-logins-failed') }}
            </p>
        @endif
    </div>

    @foreach ($users as $label => $credentials)
        <form action="{{ route('filament-developer-logins.login-as') }}" method="POST">
            <div class="fi-ac fi-width-full">
                @csrf

                <input type="hidden" name="panel_id" value="{{ \Filament\Facades\Filament::getId() }}">
                <input type="hidden" name="credentials" value="{{ $credentials }}">

                <x-filament::button type="submit" outlined="true" color="gray">
                    {{ "$label ($credentials)" }}
                </x-filament::button>
            </div>
        </form>
    @endforeach
@endif
<?php

namespace DutchCodingCompany\FilamentDeveloperLogins\Http\Requests;

use DutchCodingCompany\FilamentDeveloperLogins\Facades\FilamentDevelopersLogin;
use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;
use Filament\Facades\Filament;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginAsRequest extends FormRequest
{
    protected ?FilamentDeveloperLoginsPlugin $plugin = null;

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'panel_id' => ['required', 'string'],
            'credentials' => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return FilamentDeveloperLoginsPlugin::getById($this->get('panel_id'))->getEnabled();
    }
}

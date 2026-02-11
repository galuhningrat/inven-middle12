<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }

    public function boot()
    {
        // Single role
        Blade::directive('role', function ($role) {
            return "<?php if(Auth::check() && strtolower(Auth::user()->role->nama_role) === strtolower($role)): ?>";
        });
        Blade::directive('endrole', function () {
            return "<?php endif; ?>";
        });

        // Multi role
        Blade::directive('hasrole', function ($roles) {
            return "<?php if(Auth::check() && in_array(strtolower(Auth::user()->role->nama_role), array_map('strtolower', (array) {$roles}))): ?>";
        });
        Blade::directive('endhasrole', function () {
            return "<?php endif; ?>";
        });
    }
}

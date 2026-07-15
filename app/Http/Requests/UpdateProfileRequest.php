<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'min:3', 'max:155'],
            'email' => ['required', 'string', 'max:255'],
            'current_password' => ['nullable', 'required_with:password', function ($attribute, $value, $fail) {
                if ($this->filled('password') && !Hash::check($value, Auth::user()->password)) {
                    $fail('La contraseña actual no es válida.');
                }
            }],
            'password' => ['nullable', 'confirmed', 'min:6', 'max:8'],
        ];
    }
}

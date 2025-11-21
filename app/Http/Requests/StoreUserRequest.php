<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nombre' => 'required | max:255 | min:3',
            'rol' => 'required', 
            'file' => 'mimes:png,jpg',
            'email' => 'required | max:255 | min:3 | unique:users',
            'password' => 'min:6 | max:8',
        ];
    }
}

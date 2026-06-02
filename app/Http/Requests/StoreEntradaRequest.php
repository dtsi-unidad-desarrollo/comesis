<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEntradaRequest extends FormRequest
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
            'cedula' => 'nullable|string',
            'marcar' => 'nullable|string',
            'atm_id' => 'nullable|exists:atms,id',
            'allowed_by_user_id' => 'nullable|exists:users,id',
        ];
    }
}

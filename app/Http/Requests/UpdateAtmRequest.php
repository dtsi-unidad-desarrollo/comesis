<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAtmRequest extends FormRequest
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
            'nombre' => 'sometimes|required|string|max:255',
            'mac_address' => 'nullable|string|max:50',
            'ip_address' => 'nullable|ip',
            'torniquete_id' => 'nullable|exists:torniquetes,id',
            'descripcion' => 'nullable|string',
        ];
    }
}

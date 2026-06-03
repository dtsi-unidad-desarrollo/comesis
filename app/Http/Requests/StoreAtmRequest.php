<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAtmRequest extends FormRequest
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
            'nombre' => 'required|string|max:255|unique:atms,nombre',
            'mac_address' => 'nullable|string|max:50|unique:atms,mac_address',
            'ip_address' => 'nullable|ip|unique:atms,ip_address',
            'torniquete_id' => 'nullable|exists:torniquetes,id',
            'descripcion' => 'nullable|string',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComensaleRequest extends FormRequest
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
            "cedula"        => "required | numeric | unique:comensales",
            "file"          => "nullable | max:2048 | mimes:jpg,bmp,png",
            "nombres"       => "required | string | max:255", 
            "apellidos"     => "required | string | max:255", 
            "nacionalidad"  => "required | string", 
            "sexo"          => "required | string", 
            "tipo_comensal" => "required | string",
            "observacion"   => "nullable | string | max:500", 
        ];
    }
}

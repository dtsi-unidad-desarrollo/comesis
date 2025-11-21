<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEstudianteRequest extends FormRequest
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
            "cedula"            => "numeric",
            "file"              => "max:2048 | mimes:jpg,bmp,png",
            "nombres"           => "max:255  | required", 
            "apellidos"         => "max:255  | required", 
            "nacionalidad"      => "required", 
            "telefono"          => "min:11 | required",
            "correo"            => "max:500 | required",
            "fecha_nacimiento"  => "required",
            "carrera"           => "max:255",
            "semestre"          => "numeric",
            "cargo"             => "max: 255",
            "observacion"       => "max: 500",
            'tipo'              => "required"
            
        ];
    }
}

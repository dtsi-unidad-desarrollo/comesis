<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ComensalesTemplateExport implements FromArray, WithHeadings, ShouldAutoSize
{
    /**
     * Return an array of rows. Empty - template only contains headings.
     *
     * @return array
     */
    public function array(): array
    {
        return [];
    }

    /**
     * Headings matching the template structure
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'nombres',
            'apellidos',
            'nacionalidad',
            'cedula',
            'sexo',
            'tipo',
            'descripcion',
        ];
    }
}

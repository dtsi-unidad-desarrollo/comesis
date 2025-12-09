<?php

namespace App\Exports;

use App\Models\Comensale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ComensalesExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
     * Return a collection of rows to export.
     * We'll map model fields to the template headers.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Comensale::select([
            'nombres',
            'apellidos',
            'nacionalidad',
            'cedula',
            'sexo',
            'tipo_comensal',
            'observacion'
        ])->get()->map(function ($c) {
            return [
                'nombres' => $c->nombres,
                'apellidos' => $c->apellidos,
                'nacionalidad' => $c->nacionalidad,
                'cedula' => $c->cedula,
                'sexo' => $c->sexo,
                'tipo' => $c->tipo_comensal,
                'descripcion' => $c->observacion,
            ];
        });
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

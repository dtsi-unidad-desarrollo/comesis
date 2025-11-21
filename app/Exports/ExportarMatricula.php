<?php

namespace App\Exports;

use App\Models\GrupoEstudiante;
use App\Models\Helpers;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportarMatricula implements FromCollection, ShouldAutoSize
{
    protected $codigoGrupo;

    function __construct($codigoGrupo)
    {
        $this->codigoGrupo = $codigoGrupo;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $grupo = Helpers::getGrupos($this->codigoGrupo)[0];

        $matricula = [];

        foreach ($grupo->estudiantes as $key => $estudiante) {
            array_push($matricula,[
                $key + 1, // numero
                $estudiante->estudiante_nombre,
                $estudiante->estudiante_nacionalidad ."-". $grupo->profesor_cedula,
                $estudiante->estudiante_telefono,
                $estudiante->estudiante_correo,
                $estudiante->estudiante_edad,
                $estudiante->estudiante_nacimiento,
                $estudiante->inscripcion->proxima_fecha_pago == "PAGADO" ? "NO" : "SI",
                $estudiante->inscripcion->proxima_fecha_pago,
            ]);
        }

        return new Collection([
            ['Grupo:', $grupo->nombre, 'Código:', $grupo->codigo, 'Nivel:', $grupo->nivel_nombre],
            ['Profesor'],
            ['Nombre:', $grupo->profesor_nombre, 'Cédula:', $grupo->profesor_nacionalidad ."-". $grupo->profesor_cedula, "Teléfono:", $grupo->profesor_telefono, "Correo:", $grupo->profesor_correo ],
            ['Horario'],
            ['Días:', $grupo->dias, 'Hora:', $grupo->hora_inicio ." hasta ". $grupo->hora_fin, "Fecha de inicio:", $grupo->fecha_inicio, 'Fecha de culminación:', $grupo->fecha_fin],
            ['Estudiantes'],
            ['N°', 'Nombre y apellido', 'Cédula', 'Teléfono', 'Correo', 'edad', 'Cumpleaños', 'Pago Pendiente', 'Fecha de próximo pago' ],
            $matricula
        ]);
    }
}

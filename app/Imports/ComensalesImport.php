<?php

namespace App\Imports;

use App\Models\Comensale;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class ComensalesImport implements ToCollection, WithHeadingRow, WithChunkReading, WithCustomCsvSettings
{
    protected $errors = [];
    /**
     * Process collection of rows from the sheet
     *
    * Expected heading row with columns: nombres, apellidos, nacionalidad, cedula, tipo, estatus
     * Columns not present will be treated as null/defaults.
     */
    public function collection(Collection $rows)
    {
        $rowNumber = 1 + 1; // headings + start at 2
        foreach ($rows as $row) {
            $data = [
                'nombres' => isset($row['nombres']) ? trim($row['nombres']) : null,
                'apellidos' => isset($row['apellidos']) ? trim($row['apellidos']) : null,
                'nacionalidad' => isset($row['nacionalidad']) ? trim($row['nacionalidad']) : null,
                'cedula' => isset($row['cedula']) ? trim($row['cedula']) : null,
                'sexo' => isset($row['sexo']) ? strtoupper(trim($row['sexo'])) : '',
                // map "tipo" column to the DB column `tipo_comensal`
                'tipo_comensal' => isset($row['tipo']) ? strtoupper(trim($row['tipo'])) : 'ESTUDIANTE',
                // descripcion en la plantilla -> campo 'observacion' en DB (opcional)
                'observacion' => isset($row['descripcion']) ? trim($row['descripcion']) : null,
                // estatus siempre sera 1 al importar desde plantilla
                'estatus' => 1,
            ];

            // Skip rows without cedula
            if (empty($data['cedula'])) {
                $this->errors[] = "Fila {$rowNumber}: cedula vacía — fila omitida.";
                $rowNumber++;
                continue;
            }

            // sexo es obligatorio y debe ser M o F
            if (!in_array($data['sexo'], ['M', 'F'])) {
                $this->errors[] = "Fila {$rowNumber}: sexo inválido ('{$data['sexo']}') — debe ser 'M' o 'F'. Fila omitida.";
                $rowNumber++;
                continue;
            }

            $existing = Comensale::where('cedula', $data['cedula'])->first();
            if ($existing) {
                $existing->update($data);
            } else {
                Comensale::create($data);
            }

            $rowNumber++;
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Configuración para CSV: usar punto y coma como delimitador
     * Esto asegura que archivos generados en locales que usan `;` se parseen correctamente.
     */
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';',
            'enclosure' => '"',
            'escape' => '\\',
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}

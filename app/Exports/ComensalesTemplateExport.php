<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class ComensalesTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents
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

    /**
     * Register events to add data validations after sheet is created
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Max rows to apply validation to (change if you want more)
                $maxRow = 1000;

                // Lists to use in the dropdowns — edit here to change options
                $sexoList = ['M', 'F'];
                $tipoList = ['ESTUDIANTE', 'EMPLEADO', 'EVENTUAL', 'OTRO'];
                $nacionalidadList = ['V', 'E'];

                // Apply validations:
                // Column letters based on headings:
                // 'nombres'  A
                // 'apellidos' B
                // 'nacionalidad' C
                // 'cedula' D
                // 'sexo' E
                // 'tipo' F
                // 'descripcion' G

                $this->applyValidationToColumn($sheet, 'C', 2, $maxRow, $nacionalidadList); // nacionalidad
                $this->applyValidationToColumn($sheet, 'E', 2, $maxRow, $sexoList); // sexo
                $this->applyValidationToColumn($sheet, 'F', 2, $maxRow, $tipoList); // tipo

                // Optional: freeze header row for convenience
                $sheet->freezePane('A2');
            },
        ];
    }

    /**
     * Apply a list validation to each cell of a column in a row range
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @param string $columnLetter
     * @param int $startRow
     * @param int $endRow
     * @param array $values
     * @return void
     */
    private function applyValidationToColumn($sheet, string $columnLetter, int $startRow, int $endRow, array $values): void
    {
        // Build formula string with quoted, comma-separated values
        // Example: "M,F"
        $formula = '"' . implode(',', $values) . '"';

        for ($row = $startRow; $row <= $endRow; $row++) {
            $cell = $columnLetter . $row;

            $validation = new DataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setFormula1($formula);

            $sheet->setDataValidation($cell, $validation);
        }
    }
}
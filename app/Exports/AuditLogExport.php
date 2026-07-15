<?php

namespace App\Exports;

use App\Models\AuditLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AuditLogExport implements FromCollection, WithHeadings
{
    protected $auditorias;

    public function __construct($auditorias)
    {
        $this->auditorias = $auditorias;
    }

    public function collection()
    {
        return $this->auditorias->map(function ($registro) {
            return [
                'Fecha/Hora' => $registro->created_at->format('d/m/Y H:i:s'),
                'Usuario' => $registro->user_name ?? 'Sistema',
                'Acción' => $registro->action,
                'Entidad' => ($registro->entity_type ?? '-') . ($registro->entity_id ? ' #' . $registro->entity_id : ''),
                'Detalles' => $registro->details ? json_decode($registro->details, true)['message'] ?? $registro->details : '-',
                'IP' => $registro->ip_address ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return ['Fecha/Hora', 'Usuario', 'Acción', 'Entidad', 'Detalles', 'IP'];
    }
}

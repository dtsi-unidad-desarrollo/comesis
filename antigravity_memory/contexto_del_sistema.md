# Contexto del Sistema

## Descripción General
El proyecto "comedor" es una aplicación web basada en PHP y el framework Laravel 8. 

## Stack Tecnológico
- **Backend**: PHP (^7.3|^8.0), Laravel Framework (^8.75)
- **Frontend**: Blade Templates, Laravel Mix, Axios, Chart.js (^4.5.1)
- **Reportes/Exportación**: 
  - `barryvdh/laravel-dompdf` para generación de PDFs.
  - `maatwebsite/excel` para importación/exportación de Excel.
- **Autenticación/API**: `laravel/sanctum`

## Patrón de Diseño Arquitectónico y UI
- **MVC (Model-View-Controller)**: El proyecto sigue la estructura estándar de Laravel.
- **Thin Controllers & Fat Models / Services**: Se recomienda mantener los controladores ligeros, delegando la lógica de negocio compleja a clases de Servicio (Service Classes) o Acciones (Actions).
- **Regla Estricta de Diseño y Contraste UI**:
  - **NUNCA agregar letras o textos blancos sobre un fondo blanco** (ni sobre fondos claros/grises pálidos sin suficiente contraste).
  - Los textos sobre fondos claros deben usar siempre tipografía oscura (`text-dark`, `text-secondary`, `text-muted` o similares).
  - El texto blanco (`text-white`) se reserva estrictamente para fondos oscuros o degradados institucionales.

## Tareas Programadas y Comandos (Console/Schedule)
- `db:backup`: Comando Artisan para respaldo completo de la base de datos (estructura y datos en formato `.sql`), guardado en `storage/app/backups/` e identificado con timestamp (`backup_{database}_{Y-m-d_H-i-s}.sql`). Programado trimestralmente (`$schedule->command('db:backup')->quarterly()`).
- `ProcessSyncEmpleados`: Sincronización diaria a las 14:45.
- `ProcessSyncEstudiantes`: Sincronización diaria a las 15:45.

## Propósito de este directorio
Este directorio (`antigravity_memory`) sirve para almacenar el estado y contexto continuo del sistema, decisiones arquitectónicas, ideas y anotaciones importantes. Antigravity utilizará esto como referencia para no perder el contexto de las decisiones pasadas.


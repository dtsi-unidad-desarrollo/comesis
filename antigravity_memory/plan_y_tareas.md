# Plan y Tareas del Proyecto (Comedor)

Este documento mantiene el estado de las tareas actuales, el roadmap a corto/mediano plazo y los planes de implementación.

## Estado Actual
- [x] Análisis del proyecto y definición del stack tecnológico.
- [x] Creación de carpeta de memoria (`antigravity_memory`) para el contexto continuo.
- [x] Configuración de reglas y patrones de diseño en `.agents/rules/laravel_rules.md`.
- [x] Módulo de Apoyos Institucionales (Migración, Modelo, Controlador, Vistas uniformes, Generador de Códigos, Tickets PDF estilo entrada de cine en cuadrícula de 20 tickets por página para ahorro de papel con QR y numeración correlativa).
- [x] Integración de Apoyos en el flujo de Recepción y Control de Entrada (`RecepcionController` y `EntradaController`).
- [x] Refactorización y optimización modular de `RecepcionController.php`.
- [x] Tarea programada de respaldo automático de la base de datos cada 3 meses (`db:backup` programado `quarterly()` con guardado en `storage/app/backups/`).
- [x] Rediseño y optimización integral del Cpanel / Dashboard Administrativo (`DashboardController.php` y `resources/views/admin/dashboard.blade.php`):
  - Banner interactivo de bienvenida y estado de servicio en vivo con atajos rápidos.
  - Tarjetas de KPIs modernizadas (Bandejas de hoy, Capacidad/Cupos, Padrón de comensales, Apoyos institucionales con barras de progreso).
  - Gráfico de dona (Doughnut) de distribución de comensales hoy (incluyendo Apoyos y Disponibles).
  - Gráfico de barras agrupadas semanal (Lunes a Domingo con desglose Estudiantes, Empleados, Apoyos).
  - Gráfico de línea/área de tendencia y evolución mensual con promedios y total del mes.
  - Monitor de accesos en vivo (Últimas entradas con badges por tipo de comensal).
- [x] Reactivación y optimización del módulo de sincronización con DUX (Estudiantes) y TEREPAIMA (Empleados):
  - Vista dedicada en `/configuracion/sincronizar-data` (`resources/views/admin/configuracion/sincronizar.blade.php`).
  - Métodos `sincronizarDataPage`, `sincronizarEstudiantes`, `sincronizarEmpleados`, `sincronizarData` y `sincronizarDataStatus` en `ComensaleController.php`.
  - Soporte multi-esquema para Terepaima (`personal` y `rrhh_personal`/`rrhh_vista_personal`).
  - Modal interactivo de sincronización en el listado de comensales.
  - Enlace directo en el menú lateral de navegación (Sidebar).





## Roadmap / Próximos Pasos

### Fase 1: Base de Datos y Modelos
1. Definir los modelos principales basados en la estructura del sistema (ej: `Comensal`, `Sede`, `Asistencia`/`Comida`).
2. Configurar relaciones en Eloquent.

### Fase 2: Autenticación y Autorización
1. Configurar roles y permisos (ej: Administrador del comedor, Lector/Reportería, Empleado/Comensal).

### Fase 3: Módulos Principales
1. **Módulo de Comensales:** CRUD de personas y sincronización/carga masiva de datos (usando Excel/JSON).
2. **Módulo de Registro:** Lógica para registrar cuando un comensal recibe comida.
3. **Módulo de Reportes:** Exportación de PDF y Excel con métricas mediante Chart.js.

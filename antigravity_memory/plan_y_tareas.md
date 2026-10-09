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
- [x] Optimización y desagregación integral de Reportes de Comedor (`ReporteComedorService.php`, `EntradaController.php`, y vistas PDF asociadas):
  - Creación de servicio modular `app/Services/ReporteComedorService.php` para centralizar cálculos matemáticos y desagregación de comensales.
  - Desagregación por género (Femenino / Masculino) para Estudiantes y Empleados, e inclusión completa de Apoyos Institucionales y Eventuales en todos los reportes.
  - Corrección de cálculos matemáticos:
    - Reporte Diario: cálculo exacto de comidas entregadas según filtros, capacidad de servicio y bandejas sin entregar (`max(0, Capacidad - Entregadas)`).
    - Reporte Semanal: desglose día a día (Lunes a Sábado), sumas de columnas horizontales y verticales consistentes, capacidad semanal y bandejas sin entregar.
    - Reporte Mensual: cálculo de totales por columna (antes en blanco), integración de Apoyos Institucionales (antes omitidos) y cuadro de porcentajes.
    - Reporte Semanas del Mes: partición de semanas que cubre el 100% de los días del mes sin fugas ni desfases, logrando sincronización matemática idéntica con el Reporte Mensual.
  - Plantillas PDF modernizadas y formalizadas para la universidad (UNELLEZ), con encabezados oficiales, logos, resumen de métricas y firmas institucionales.
- [x] Rediseño y modernización de la interfaz del Módulo de Entradas / Historial (`index.blade.php` y partials):
  - Banner interactivo de encabezado con degradado institucional e integración directa del Centro de Reportes tipo pill badges.
  - Tarjetas de KPIs rápidos de hoy (Total Entradas, Estudiantes hoy, Empleados hoy, Apoyos hoy).
  - Barra de búsqueda y filtros avanzada con selectores estilizados y botón de restablecer vista.
  - Tabla de registros modernizada: avatares con iniciales por tipo de comensal, badges semánticos por tipo (Estudiante, Empleado, Apoyo), badges de servicio con icono de sol/luna, fechas formateadas y acciones con tooltips.
  - Modales de detalle y eliminación rediseñados con tarjetas de perfil y alertas claras.
- [x] Rediseño y modernización del Módulo de Comensales (`ComensaleController.php`, `resources/views/admin/comensales/index.blade.php` y partials):
  - Retiro definitivo del botón/modal de sincronización con DUX/Terepaima de la vista de comensales (centralizado en la sección de configuraciones `/configuracion/sincronizar-data`).
  - Banner institucional superior con títulos, badges y botón de registro de comensal.
  - Tarjetas de KPIs de padrón: Total Padrón, Estudiantes, Personal/Empleados, Comensales Activos/Inactivos.
  - Filtros avanzados: búsqueda textual (cédula, nombres o apellidos), filtro por tipo de comensal (Estudiante, Profesor, Administrativo, Obrero, Empleado, etc.) y filtro por estatus (Activo/Inactivo).
  - Modal estilizado para importación masiva desde Excel (`.xlsx, .xls, .csv`) y menú dropdown ordenado para herramientas de Excel (Exportar, Importar, Plantilla).
  - Tabla de comensales estilizada con avatares (con foto o inicial por tipo), badges de género (F/M), badges semánticos de tipo y estatus, y botones circulares de acción rápida (Ver ficha, Editar, Desactivar/Eliminar).


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

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
  - Modernización estética de los modales de Registro y Edición de Comensales (`modalFormulario.blade.php` y `modalFormularioEditar.blade.php`) con diseño centrado (`modal-dialog-centered modal-lg`), esquinas redondeadas, cabeceras institucionales, campos con iconos armonizados y pie con botones tipo pill.
  - Simplificación del selector **Tipo de Comensal** en los formularios de registro y edición: se redujeron las opciones a los dos tipos globales del sistema: **ESTUDIANTE** y **EMPLEADO**.
  - Flexibilización del campo **Observación**: configurado como opcional (`nullable|string|max:500`) tanto en frontend (textarea completo sin restricción `minlength`) como en backend (`StoreComensaleRequest.php` y `UpdateComensaleRequest.php`).
- [x] Modernización integral del Menú Superior (Header) y Menú Lateral (Sidebar):
  - **Header Superior (`header.blade.php` y `style.css`):**
    - Dimensionamiento óptimo y nítido del logo COMESIS con badge institucional UNELLEZ.
    - Botón de toggle lateral circular estilizado con microinteracción hover.
    - Acceso directo a punto de control / recepción y widget con fecha actual en tiempo real.
    - Menú de perfil de usuario con indicador de estado conectado (punto verde), tarjeta informativa del usuario (nombre, rol y correo), enlaces directos y logout limpio con estilo moderno.
  - **Sidebar Lateral (`sidebar.blade.php` y `style.css`):**
    - Eliminación de colores antiguos y clases inconsistentes (se reemplazó el vino tinto `#9E2035`, el naranja apagado `#E9932F` y los bloques azules toscos).
    - Agrupación semántica en tres secciones con encabezados estilizados: *Principal*, *Gestión de Comedor*, y *Sistema*.
    - Contenedores de iconos estandarizados (`nav-icon-wrap`) con dimensiones uniformes de 32x32px y esquinas redondeadas.
    - Árbol de submenús con línea lateral conector/guía, enlaces estilizados con hover suave e indicador activo de submenú (`.active`) con acento azul primario.
    - Tarjeta inferior del sidebar con versión del sistema COMESIS v2.5 e indicador de estado de servidor.
- [x] Modernización integral del Módulo de Apoyos Institucionales (`ApoyoController.php`, `index.blade.php`, `create.blade.php`, `edit.blade.php`):
  - Banner institucional superior con títulos, badges descriptivos y botón de nuevo apoyo tipo pill.
  - Tarjetas de KPIs de convenios: Total Convenios, Comidas Habilitadas, Comidas Consumidas, y Disponibles Restantes.
  - Barra de búsqueda y filtros avanzada (búsqueda por institución o código, filtro por estatus activo/inactivo).
  - Tabla estilizada con avatares temáticos, códigos QR en badge monoespaciado con botón rápido de copiar, barras de progreso mini para el consumo de cupos, badges semánticos de estatus y botones circulares de acción.
  - Modales de confirmación de eliminación integrados por registro (reemplazando los `confirm()` nativos del navegador).
  - Rediseño de formularios de registro y edición (`create.blade.php` y `edit.blade.php`) con generador automático de códigos QR, alertas de consumo previo y validaciones visuales.
- [x] Modernización integral de todas las secciones del submenú Configuración (`resources/views/admin/`):
  1. **Servicios y Turnos (`admin.servicios.index`):** Banner institucional, KPIs de capacidad y turnos, tabla estilizada con badges de estado, modales de creación, edición y confirmación de desactivación modernizados.
  2. **Gestión de Usuarios (`admin.users.index`):** Banner institucional con botón de nuevo usuario, tarjetas KPI de roles, tabla con avatares fotográficos e iniciales, badges de roles/estado, y formularios de creación/edición rediseñados (`crear.blade.php`, `editar.blade.php`).
  3. **Roles de Usuario (`admin.roles.index`):** Banner superior, tabla de roles con contador y listado de permisos asociados en badges suaves, modal de creación y vista de edición con tarjetas de selección de permisos (`edit.blade.php`).
  4. **Permisos y Accesos (`admin.permisos.index`):** Banner superior con modal de creación rápida de permisos, tabla con nombre de permiso en fuente monoespaciada y badges, vista de edición pulida (`edit.blade.php`).
  5. **Torniquetes Físicos (`admin.torniquetes.index`):** Banner institucional, tabla con endpoint/IP de control, estado y acciones, formularios compartidos (`_form.blade.php`), creación (`create.blade.php`) y edición (`edit.blade.php`).
  6. **Taquillas / ATMs (`admin.atms.index`):** Banner, tabla con vinculación a torniquetes físicos, botón de prueba de apertura rápida por hardware (pulso), formularios compartidos (`_form.blade.php`), creación (`create.blade.php`) y edición (`edit.blade.php`).
  7. **Registro de Auditoría (`admin.auditoria.index`):** Banner institucional con exportación a Excel y PDF, panel de filtros colapsable con búsqueda y selectores, tabla estilizada con badges semánticos por acción (Crear, Editar, Eliminar, Iniciar Sesión) y detalle de IP/agente.
  8. **Lista Blanca de IPs (`admin.allowed-ips.index`):** Banner, formulario de registro rápido en card lateral/superior, tabla con IP monoespaciada, toggles visuales de estado y modales de confirmación.
  9. **Sincronización Remota (`admin.sincronizar.data.index`):** Banner institucional, tarjetas DUX y TEREPAIMA con estado de conexión, contadores de padrón y tabla de programación de Cron Jobs estilizada.
- [x] Diagnóstico integral de gestión de sesiones de usuario y ejecución de mejoras de seguridad y resiliencia (`plan_mejora_sesiones.md`):
  - **Fase 1 (Resiliencia Operativa):**
    - Migración de `SESSION_DRIVER=file` a `SESSION_DRIVER=database` con creación de la tabla `sessions` en MySQL.
    - Implementación de rastreo de usuario y actividad en taquillas (`atms`: `user_id`, `last_active_at`), eliminando el bloqueo huérfano de ATMs cuando un usuario cierra el navegador o expira su sesión.
    - Sincronización automática y auto-liberación de taquillas inactivas (>20-30 min) en `RecepcionController.php`, `HandleAtm.php` y `LoginController.php`.
    - Optimización de alto rendimiento en el middleware `ValidarRol.php`: ahora consulta `session('user_permisos_map')` con lookup O(1) en vez de consultar `Role::find()` a MySQL en cada petición HTTP.
  - **Fase 2 (Control y Auditoría de Sesiones):**
    - Auditoría de intentos fallidos de autenticación (`login_failed`) en `AuditLogger.php` y protección contra fuerza bruta con rate limiting (`throttle:5,1`) en la ruta de inicio de sesión.
    - Monitor de sesiones en vivo en `admin/auditoria/index.blade.php`: pestaña dedicada para visualizar usuarios conectados en tiempo real, dispositivo, IP, taquilla asociada y botón para revocar/desconectar sesiones sospechosas.
  - **Fase 3 (UX y Prevención de Desconexiones):**
    - Modal global de aviso de inactividad (`resources/views/partials/idle-timeout.blade.php`): contador regresivo de 60 segundos tras 15 minutos sin interacción y botón de "Mantener Sesión Activa" vía endpoint `/session/keepalive`.
    - Interceptor global para error 419 (Page Expired) con redirección limpia a `/login?expired=1` y alertas estilizadas en login desktop y mobile.
    - Gestión de sesiones y dispositivos conectados en el perfil del usuario (`admin/perfil/index.blade.php`): visualización de la sesión actual y botón para revocar sesiones en otros dispositivos (`revokeOtherSessions`), con revocación automática al cambiar la contraseña.
- [x] Configuración de Icono del Sistema, Favicons Multi-resolución y Soporte PWA para Instalación de Escritorio:
  - Generación de suite completa de iconos cuadrados transparentes de alta resolución basados en el nuevo logo 3D "C" (`public/assets/img/icons/`): `favicon-16x16.png`, `favicon-32x32.png`, `favicon-48x48.png`, `apple-touch-icon.png` (180x180), `icon-192x192.png`, `icon-512x512.png` e `icon-maskable-512x512.png`.
  - Creación del archivo binario estándar `public/favicon.ico` con soporte embebido multi-tamaño (16x16, 32x32, 48x48) y actualización de `public/favicon.png` y `public/assets/img/logo-img-circulo.png`.
  - Configuración del manifiesto de aplicación web (`public/manifest.json`) y etiquetas `<meta>` para PWA / accesos directos de escritorio (`resources/views/partials/head.blade.php`).
  - Creación de Service Worker (`public/sw.js`) con estrategia network-first para habilitar la instalación nativa de la app en navegadores Chromium (Chrome, Edge) en Windows Desktop y dispositivos móviles, mostrando el logo oficial en el escritorio, barra de tareas y ventana independiente.
- [x] Modernización integral y depuración de las Vistas de Login (`resources/views/`):
  - **Eliminación de archivo obsoleto:** Se eliminó definitivamente el archivo en desuso `resources/views/login.blade.php` (no estaba referenciado por ningún controlador ni ruta).
  - **Coherencia visual con el nuevo logo 3D ("C" azul/zafiro):** Se reemplazó el esquema antiguo de degradados morados/violetas (`#667eea`, `#764ba2`) por la paleta institucional profunda de azul zafiro, azul marino y degradados radiales oscuros con iluminación sutil.
  - **Modernización de `login-desktop.blade.php`:**
    - Panel lateral institucional con halo lumínico de fondo, tarjeta translúcida del logo 3D, títulos limpios, badge de la UNELLEZ, pilares del sistema con iconos temáticos y pie de versión institucional.
    - Panel de formulario estilizado con inputs modernos, iconos alineados, toggle interactivo para ver/ocultar contraseña, y alertas estilizadas de expiración de sesión por inactividad (`?expired=1`) y errores de credenciales.
    - **Alineación y estilo:** Fondo exterior unificado con el azul institucional de la tarjeta (`linear-gradient(145deg, #0b347b 0%, #05193d 100%)`), nombre COMESIS dispuesto horizontalmente al lado del logo, línea divisoria ultrafina y sutil (`1px solid rgba(255,255,255,0.08)`), y cajas de iconos coloridas y temáticas para cada característica del sistema.
  - **Modernización de `login-mobile.blade.php`:**
    - Encabezado móvil con vitrina translúcida del logo 3D alineado junto a COMESIS, badge institucional y tipografía optimizada para pantallas pequeñas.
    - Fondo armonizado con el mismo degradado azul de la tarjeta, divisores sutiles, tarjeta centrada con inputs amplios (`inputmode="email"`, autofoco, password toggle) y botón de acceso principal ergonómico con degradado azul y sombras suaves.
    - Soporte integrado de alertas de expiración de sesión y credenciales inválidas.
- [x] Restricción de Seguridad y Aislamiento del Rol ROOT en Gestión de Usuarios y Roles:
  - **Aislamiento en Listados (`Helpers::getUsuarios()`):** Cuando un usuario con rol de Administrador (o cualquier rol que no sea ROOT) inicia sesión, el usuario ROOT no es consultado ni listado en la tabla de usuarios.
  - **Aislamiento en Asignación de Roles (`UserController`):**
    - En la vista de creación y edición (`crear.blade.php` y `editar.blade.php`), el select de roles omite completamente el rol ROOT para administradores.
    - Validación y protección a nivel de controlador (`store` y `update`) impidiendo que un administrador pueda asignar el rol ROOT (`abort(403)`).
  - **Protección de Edición y Eliminación:** Se bloquea el acceso de administradores a la edición o eliminación de cuentas con rol ROOT por URL o formulario, y se previene que cualquier usuario pueda eliminar su propia cuenta o al superusuario ROOT.
  - **Aislamiento en Roles de Usuario (`RoleController` y vista `admin/roles/index.blade.php`):** El rol ROOT queda oculto en el listado para administradores y protegido contra edición y eliminación en backend y frontend.
- [x] Gestión y Liberación Manual de Taquillas / ATMs Huérfanos (`admin/atm/index.blade.php`, `AtmController.php`):
  - **Indicador de Estado en Tiempo Real:** Columna dedicada *Estado / Turno* que muestra si la taquilla está `DISPONIBLE` o `EN USO`, con el nombre del cajero u operador asignado y el tiempo de su última actividad registrada.
  - **Botón de Desvinculación de Turno:** Botón circular interactivo con icono de desbloqueo (`bi-unlock-fill`) visible en la fila de cada taquilla ocupada (deshabilitado si está libre).
  - **Modal de Confirmación y Seguridad:** Ventana modal de confirmación con detalles del operador antes de forzar el cierre del turno huérfano.
  - **Backend y Auditoría:** Endpoint `POST /atms/{atm}/release` (`admin.atms.release`) ejecutando `$atm->release()` y registrando el evento en `AuditLogger` con el nombre del operador y taquilla liberada.
- [x] Filtro por Tipo de Comensal y Visualización de Subtipo en Historial de Entradas (`admin/entradas/index.blade.php`, `EntradaController.php`, `Entrada.php`):
  - **Filtro dinámico por Tipo:** Selector con todos los tipos disponibles en el sistema (Estudiante, Empleado, Estudiante Foráneo, Profesor, Administrativo, Obrero, Eventual, Apoyo y tipos registrados dinámicamente en BD).
  - **Columna de Subtipo Registrado:** Nueva columna en la tabla principal de entradas y en el modal de detalles que muestra el subtipo del comensal (`sub_tipo_display`, con fallback a relación Eloquent `comensal->sub_tipo` o `CONVENIO` para apoyos).
  - **Migración y Persistencia:** Migración `add_sub_tipo_to_entradas_table` con backfill automático desde `comensales`, e inclusión de `sub_tipo` al registrar accesos en `Helpers::setEntradaComedor`, `RecepcionController` y `EntradaController`.





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

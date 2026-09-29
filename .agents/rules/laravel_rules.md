---
description: Reglas de desarrollo y convenciones para el proyecto Comedor (Laravel 8).
---

# Reglas del Proyecto Comedor

Estas reglas se aplican automáticamente a todo el desarrollo realizado en este proyecto.

## 1. Patrones de Arquitectura
- **MVC Tradicional**: Utilizar el patrón Modelo-Vista-Controlador de Laravel.
- **Controladores Ligeros**: Mantener los controladores delgados. Si la lógica de negocio supera las 20-30 líneas en una función de controlador, encapsularla en una clase de Servicio (Service) en `app/Services/` o una clase Action en `app/Actions/`.
- **Form Requests**: Para la validación de formularios y peticiones, utilizar siempre `FormRequests` en lugar de validar directamente en el controlador.

## 2. Convenciones de Código (PHP/Laravel)
- **Estándar**: Seguir estrictamente el estándar PSR-12.
- **Nomenclatura**:
  - Modelos y Controladores: `PascalCase` y en singular para Modelos (`User`, no `Users`), plural para Controladores (`UsersController`).
  - Métodos y Variables: `camelCase`.
  - Rutas y Nombres de Rutas: kebab-case para URLs (`/reportes-mensuales`) y snake_case con puntos para los nombres de las rutas (`reportes.mensuales`).
  - Tablas de Base de Datos y Columnas: `snake_case` (tablas en plural, columnas en singular).
- **Tipado**: Especificar los tipos de retorno de los métodos y los tipos de los argumentos de las funciones donde sea posible.

## 3. Frontend (Blade & Mix)
- Mantener las vistas Blade ordenadas y usar componentes (`<x-component>`) o includes (`@include`) para secciones repetitivas.
- Usar `Laravel Mix` (`webpack.mix.js`) para compilar los assets (CSS/JS). No colocar lógica compleja de JS directamente en las plantillas Blade, usar archivos JS modulares y compilarlos.

## 4. Dependencias Clave
- **Generación de PDFs**: Utilizar la dependencia instalada `barryvdh/laravel-dompdf`.
- **Manejo de Excel**: Utilizar `maatwebsite/excel`.
- **Gráficos**: Utilizar `chart.js` (incluido en el `package.json`).

## 5. Respuestas de API y Web
- Las rutas web deben devolver Vistas (Blade) o redirecciones (Redirects).
- Las rutas de API (en `routes/api.php`) deben devolver `JsonResponse` con códigos HTTP estándar (200, 201, 400, 404, 500).

# Estructura del Sistema

Este documento describe la arquitectura técnica, los módulos principales y el esquema de datos que conforma el proyecto "Comedor".

## Entidades Principales (Draft)

Basado en la información recopilada del entorno (como el archivo `structure_query.json`), el sistema maneja la siguiente estructura inicial para los usuarios/comensales:

### 1. Comensales / Personas
Representa a las personas que asisten al comedor.
- **Campos esperados**:
  - `per_codigo`: Identificador interno (ej. 15230)
  - `per_cedula`: Documento de identidad (ej. 24823972)
  - `per_apellidos`: Apellidos (ej. OSUNA AGUIRRE)
  - `per_nombres`: Nombres (ej. YORDHIS JOSE)
  - `sexo`: Género de la persona (ej. Masculino)
  - `tipo_comensal`: Tipo de usuario del comedor (ej. EMPLEADO, ESTUDIANTE)
  - `estatus`: Estado de la persona (ej. 1 / Activo)

### 2. Sedes / Dependencias
Representa la ubicación o dependencia a la que pertenece el comensal.
- **Campos esperados**:
  - `Nombre_Completo`: Nombre de la institución/sede (ej. VICERRECTORADO DE PLANIFICACIÓN Y DESARROLLO SOCIAL(CENTRAL))
  - `Nombre`: Abreviatura (ej. CENTRAL)
  - `vicn_descripcion`: Descripción de la sede (ej. Barinas sede)

### 3. Registro de Comidas (Entradas)
Esta tabla es el núcleo transaccional. Lleva el registro de qué comensal o apoyo institucional consumió en qué fecha/hora y servicio.
- **Campos principales**:
  - `cedula`: Cédula del comensal o código de apoyo.
  - `nombres`, `apellidos`, `sexo`, `nacionalidad`.
  - `tipo_comensal`: ESTUDIANTE, EMPLEADO, APOYO, etc.
  - `sub_tipo`: Subtipo o cargo del comensal (ej. PERSONAL, DOCENTE, OBRERO, SUB_TIPO_1, CONVENIO).
  - `comida`: Servicio (ALMUERZO, CENA, etc.).
  - `fecha`, `hora`.
  - `atm_id`, `allowed_by_user_id`.

### 4. Apoyos Institucionales
Representa las autorizaciones grupales o institucionales para comensales externos mediante un código único de barras / QR.
- **Campos principales**:
  - `institucion`: Nombre de la institución.
  - `codigo`: Código identificador o código de barras/QR escaneable.
  - `cantidad_habilitada`: Límite total de comidas autorizadas.
  - `cantidad_consumida`: Contador de comidas consumidas.
  - `estatus`: 1 (Activo), 0 (Inactivo).


## Estructura de Directorios Clave (Laravel)
- `app/Http/Controllers/`: Lógica de peticiones HTTP.
- `app/Models/`: Modelos Eloquent interactuando con la BD.
- `app/Services/` *(Acrear)*: Clases donde residirá la lógica pesada de negocio.
- `resources/views/`: Plantillas Blade para el frontend.
- `routes/web.php` y `routes/api.php`: Definición de los endpoints.

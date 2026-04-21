![Logo del Sistema](public/assets/img/logo.png)

**COMESIS**
------------------------------
Es un sistema de control de acceso el cual se encarga de capturar las entradas de los
estudiantes y demás población universitaria al comedor de la UNELLEZ, permitiendo
llevar un control de los alimentos suministrados por el servicio del comedor.

## Características del sistema
- Permite validar los datos del comensal ESTUDIANTES mostrando si está activo o no en el sistema DUX.
- Permite registrar y editar comensales especiales como: ADMINISTRATIVOS, OBREROS Y EVENTUALES.
- Permite configurar los servicios del comedor.
- Permite generar reportes diarios, semanales, mensuales y por fecha personalizadas.
- Control de usuarios

## Manual de usuario
Aquí va el link del PDF

## Ficha Técnica
### Tecnologías
- PHP 8.1 o superior
- Framework Laravel 8
- Framework Bootstrap 5
- Jquery-confirm
- MySQL/MariaDB para base de datos
- Composer para gestión de dependencias PHP
- NPM para gestión de dependencias frontend

### Infraestructura
- Servidor web Apache con PHP 8.1 o superior
- Base de datos MySQL/MariaDB
- Sistema operativo: Windows/Linux (compatible con Laragon para desarrollo local)

## Despliegue
### Requisitos previos
- PHP 8.1 o superior instalado
- Composer instalado
- Node.js y NPM instalados
- MySQL/MariaDB instalado y configurado
- Git (opcional, para clonar el repositorio)

### Pasos de despliegue
1. **Clonar el repositorio** (si aplica):
   ```
   git clone <url-del-repositorio>
   cd comedor
   ```

2. **Instalar dependencias de PHP**:
   ```
   composer install
   ```

3. **Instalar dependencias de frontend**:
   ```
   npm install
   ```

4. **Configurar el entorno**:
   - Copiar el archivo `.env.example` a `.env`:
     ```
     cp .env.example .env
     ```
   - Editar `.env` con la configuración de base de datos y otras variables necesarias.

5. **Generar clave de aplicación**:
   ```
   php artisan key:generate
   ```

6. **Ejecutar migraciones de base de datos**:
   ```
   php artisan migrate
   ```

7. **Ejecutar seeders** (si es necesario para datos iniciales):
   ```
   php artisan db:seed
   ```

8. **Construir assets**:
   ```
   npm run dev
   ```
   O para producción:
   ```
   npm run prod
   ```

9. **Iniciar el servidor**:
   ```
   php artisan serve
   ```
   O configurar en un servidor web como Apache.

10. **Acceder al sistema**:
    Abrir el navegador en `http://localhost:8000` o la URL configurada.

### Notas adicionales
- Asegúrese de que los permisos de escritura estén configurados para las carpetas `storage` y `bootstrap/cache`.
- Para entornos de producción, configure un servidor web adecuado y optimice los assets.
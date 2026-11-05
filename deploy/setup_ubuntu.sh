#!/usr/bin/env bash
set -e

# ==============================================================================
# Script de Aprovisionamiento para Ubuntu 26 (Resolute) + PHP 8.5 (COMESIS)
# ==============================================================================

echo "=== 1. Limpiando repositorios PPA previos incompatibles con Ubuntu 26 ==="
# Elimina cualquier entrada fallida de ondrej con suite 'resolute' que cause error 404
sudo rm -f /etc/apt/sources.list.d/*ondrej*

echo "=== 2. Actualizando repositorios oficiales del sistema ==="
sudo apt-get update
sudo apt-get install -y software-properties-common curl git unzip nginx supervisor mysql-server ca-certificates gnupg lsb-release

CODENAME=$(lsb_release -sc 2>/dev/null || echo "resolute")
echo "Distribución detectada: $CODENAME"

echo "=== 3. Configurando repositorio de PHP 8.5 ==="
# Verificamos si php8.5 está en los repos oficiales de Ubuntu 26
if apt-cache show php8.5-cli >/dev/null 2>&1; then
    echo "PHP 8.5 está disponible directamente en los repositorios oficiales de Ubuntu."
else
    echo "Configurando PPA Ondřej Surý adaptado para Ubuntu 26 (fallback suite: noble)..."
    sudo mkdir -p /etc/apt/keyrings
    # Descargar e importar la llave GPG oficial de Ondřej Surý PPA
    curl -fsSL "https://keyserver.ubuntu.com/pks/lookup?op=get&search=0x14AA40EC0831756756D7F66C4F4EA0AAE5267A6C" | \
        sudo gpg --dearmor --yes -o /etc/apt/keyrings/ondrej-php.gpg

    # Registrar la fuente APT deb822 apuntando a la rama LTS compatible (noble)
    sudo tee /etc/apt/sources.list.d/ondrej-php.sources > /dev/null << 'EOF'
Types: deb
URIs: https://ppa.launchpadcontent.net/ondrej/php/ubuntu
Suites: noble
Components: main
Signed-By: /etc/apt/keyrings/ondrej-php.gpg
EOF
    sudo apt-get update
fi

echo "=== 4. Instalando PHP 8.5 y extensiones necesarias para Laravel ==="
# Determinar el prefijo del paquete
if apt-cache show php8.5-cli >/dev/null 2>&1; then
    PHP_VER="8.5"
    PHP_PREFIX="php8.5"
else
    # Fallback a los paquetes genéricos de PHP provistos por la distribución
    PHP_VER=""
    PHP_PREFIX="php"
fi

sudo apt-get install -y \
    "${PHP_PREFIX}-fpm" \
    "${PHP_PREFIX}-cli" \
    "${PHP_PREFIX}-mysql" \
    "${PHP_PREFIX}-mbstring" \
    "${PHP_PREFIX}-xml" \
    "${PHP_PREFIX}-curl" \
    "${PHP_PREFIX}-zip" \
    "${PHP_PREFIX}-gd" \
    "${PHP_PREFIX}-bcmath" \
    "${PHP_PREFIX}-intl" \
    "${PHP_PREFIX}-sqlite3"

# Instalar redis si está disponible
if apt-cache show "${PHP_PREFIX}-redis" >/dev/null 2>&1; then
    sudo apt-get install -y "${PHP_PREFIX}-redis" || true
fi

# Detectar el ejecutable de PHP
PHP_BIN=$(command -v php8.5 || command -v php)
echo "=== 5. Versión instalada de PHP ==="
$PHP_BIN -v

echo "=== 6. Instalando Composer ==="
if ! command -v composer &> /dev/null; then
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    sudo chmod +x /usr/local/bin/composer
fi
composer --version

echo "=== 7. Configurando permisos de directorios ==="
PROJECT_DIR=$(pwd)
sudo chown -R www-data:www-data "$PROJECT_DIR/storage" "$PROJECT_DIR/bootstrap/cache"
sudo chmod -R 775 "$PROJECT_DIR/storage" "$PROJECT_DIR/bootstrap/cache"

echo "=== 8. Instalando dependencias del proyecto ==="
composer install --no-dev --optimize-autoloader

echo "=== 9. Optimizando caché de Laravel ==="
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache

echo "=== ¡Aprovisionamiento completado con éxito! ==="

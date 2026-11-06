#!/usr/bin/env bash
set -e

# ==============================================================================
# Generador de Certificado SSL Autofirmado para COMESIS (IP de Red Local)
# ==============================================================================

IP="192.168.3.249"
SSL_DIR="/etc/ssl/comesis"

echo "=== Creando directorio para certificados SSL ==="
sudo mkdir -p "$SSL_DIR"

echo "=== Generando certificado SSL autofirmado con SAN para IP $IP ==="
sudo openssl req -x509 -nodes -days 3650 -newkey rsa:2048 \
    -keyout "$SSL_DIR/comesis.key" \
    -out "$SSL_DIR/comesis.crt" \
    -subj "/C=VE/ST=Barinas/L=Barinas/O=UNELLEZ/OU=COMESIS/CN=$IP" \
    -addext "subjectAltName=IP:$IP,IP:127.0.0.1,DNS:localhost"

sudo chmod 600 "$SSL_DIR/comesis.key"
sudo chmod 644 "$SSL_DIR/comesis.crt"

echo "=== Certificado generado exitosamente en: $SSL_DIR ==="
ls -lh "$SSL_DIR"

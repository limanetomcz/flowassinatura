#!/bin/sh

# Script para gerar certificados SSL auto-assinados
echo "🔐 Gerando certificados SSL..."

# Instalar openssl se não estiver disponível
if ! command -v openssl >/dev/null 2>&1; then
    echo "📦 Instalando openssl..."
    apk add --no-cache openssl
fi

# Verificar se os certificados já existem
if [ ! -f /etc/nginx/ssl/cert.pem ] || [ ! -f /etc/nginx/ssl/key.pem ]; then
    echo "📝 Criando certificados SSL auto-assinados..."
    
    # Gerar certificados SSL auto-assinados
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
        -keyout /etc/nginx/ssl/key.pem \
        -out /etc/nginx/ssl/cert.pem \
        -subj "/C=BR/ST=SP/L=Sao Paulo/O=FlowAssinatura/CN=localhost"
    
    echo "✅ Certificados SSL gerados com sucesso!"
else
    echo "ℹ️  Certificados SSL já existem."
fi

echo "🚀 Iniciando nginx..."
exec nginx -g "daemon off;" 
#!/bin/sh
set -e

#!/bin/sh
set -e

CERT_PATH="/etc/letsencrypt/live/${APP_DOMAIN}/fullchain.pem"

echo "Checking SSL certificate..."

if [ ! -f "$CERT_PATH" ]; then
    echo "❌ Certificate not found → starting HTTP mode"

    envsubst '${APP_DOMAIN}' \
        < /etc/nginx/http.conf.template \
        > /etc/nginx/conf.d/default.conf

    nginx

    echo "⏳ Waiting nginx to be ready..."
    sleep 3

    echo "🚀 Requesting certificate..."

    certbot certonly \
        --webroot \
        --webroot-path=/var/www/certbot \
        -d ${APP_DOMAIN} \
        -d www.${APP_DOMAIN} \
        --email ${LETSENCRYPT_EMAIL} \
        --agree-tos \
        --no-eff-email \
        --non-interactive

    echo "♻️ Reloading nginx with SSL..."

    envsubst '${APP_DOMAIN}' \
        < /etc/nginx/https.conf.template \
        > /etc/nginx/conf.d/default.conf

    nginx -s reload

else
    echo "✅ Certificate exists → starting HTTPS"

    envsubst '${APP_DOMAIN}' \
        < /etc/nginx/https.conf.template \
        > /etc/nginx/conf.d/default.conf

    exec nginx -g "daemon off;"
fi
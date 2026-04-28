#!/bin/sh
set -e

CERT="/etc/letsencrypt/live/${APP_DOMAIN}/fullchain.pem"

build_http() {
    echo "🔵 HTTP mode"
    envsubst '${APP_DOMAIN}' \
        < /etc/nginx/http.conf.template \
        > /etc/nginx/conf.d/default.conf
}

build_https() {
    echo "🟢 HTTPS mode"
    envsubst '${APP_DOMAIN}' \
        < /etc/nginx/https.conf.template \
        > /etc/nginx/conf.d/default.conf
}

if [ "$APP_ENV" != "prod" ]; then
    build_http
    exec nginx -g "daemon off;"
fi

# PROD MODE
build_http

nginx -g "daemon off;"

echo "⏳ Starting certificate watcher..."

while true; do
    if [ -f "$CERT" ]; then
        echo "✅ Certificate found → switching to HTTPS"

        build_https
        nginx -s reload

        echo "🚀 HTTPS enabled"
        break
    fi

    echo "⌛ Waiting for certificate..."
    sleep 30
done

# keep container running
exec nginx -s reload
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

# -------------------------
# DEV MODE
# -------------------------
if [ "$APP_ENV" != "prod" ]; then
    build_http
    exec nginx -g "daemon off;"
fi

# -------------------------
# PROD MODE
# -------------------------

if [ -f "$CERT" ]; then
    build_https
else
    build_http
fi

echo "🚀 Starting nginx..."
nginx

echo "⏳ Waiting for certificate..."

while true; do
    if [ -f "$CERT" ]; then
        echo "✅ Certificate found → restarting nginx with HTTPS"

        # 1. билдим HTTPS конфиг
        build_https

        # 2. корректно останавливаем nginx
        nginx -s quit

        # 3. ждём пока реально остановится
        while pgrep nginx > /dev/null; do
            sleep 1
        done

        # 4. запускаем заново (уже HTTPS)
        exec nginx -g "daemon off;"
    fi

    sleep 30
done
#!/bin/sh
set -e

DH_PATH="/etc/letsencrypt/ssl-dhparams.pem"
SSL_OPTIONS="/etc/letsencrypt/options-ssl-nginx.conf"

build_http() {
    envsubst '${APP_DOMAIN}' \
        < /etc/nginx/http.conf.template \
        > /etc/nginx/conf.d/default.conf
}

build_https() {
    envsubst '${APP_DOMAIN}' \
        < /etc/nginx/https.conf.template \
        > /etc/nginx/conf.d/default.conf
}

if [ "$APP_ENV" != "prod" ]; then
    build_http
    exec nginx -g "daemon off;"
fi

# -----------------------------------------------
# 2. Подготавливаем SSL вспомогательные файлы
# -----------------------------------------------
mkdir -p /etc/letsencrypt /var/www/certbot

if [ ! -f "$SSL_OPTIONS" ]; then
  echo "⚙️  Creating placeholder SSL options config..."
  cat > "$SSL_OPTIONS" <<EOF
ssl_session_cache shared:le_nginx_SSL:10m;
ssl_session_timeout 1440m;
EOF
fi

if [ ! -f "$DH_PATH" ]; then
  echo "⚙️ Generating DH parameters (this may take ~20 seconds)..."
  openssl dhparam -out "$DH_PATH" 2048
fi

if [ -f "/etc/letsencrypt/live/${APP_DOMAIN}/fullchain.pem" ]; then
    build_https
else
    build_http
fi

# старт watcher в фоне
/usr/local/bin/watch-certs.sh &

# основной процесс
exec nginx -g "daemon off;"
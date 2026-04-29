#!/bin/sh
set -e

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

if [ -f "/etc/letsencrypt/live/${APP_DOMAIN}/fullchain.pem" ]; then
    build_https
else
    build_http
fi

nginx

# старт watcher в фоне
/usr/local/bin/watch-certs.sh &

# основной процесс
exec nginx -g "daemon off;"
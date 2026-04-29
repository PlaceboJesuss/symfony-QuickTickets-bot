#!/bin/sh

CERT_DIR="/etc/letsencrypt/live/${APP_DOMAIN}"

echo "👀 Watching certificate changes in $CERT_DIR"

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

while [ ! -d "$CERT_DIR" ]; do
    sleep 5
done

build_https
echo "🔁 Certificate create → reloading nginx"
nginx -s reload || true

while inotifywait -e modify,create,delete,move -r "$CERT_DIR"; do
    echo "🔁 Certificate changed → reloading nginx"
    nginx -s reload || true
done
#!/bin/sh

CERT_DIR="/etc/letsencrypt/live/${APP_DOMAIN}"

echo "👀 Watching certificate changes in $CERT_DIR"

while [ ! -d "$CERT_DIR" ]; do
    sleep 5
done

echo "🔁 Certificate create → reloading nginx"
nginx -s reload || true

while inotifywait -e modify,create,delete,move -r "$CERT_DIR"; do
    echo "🔁 Certificate changed → reloading nginx"
    nginx -s reload || true
done
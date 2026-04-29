#!/bin/sh

CERT_DIR="/etc/letsencrypt/live/${APP_DOMAIN}"

echo "👀 Watching certificate changes in $CERT_DIR"
sleep 5

while inotifywait -e modify,create,delete,move -r "$CERT_DIR"; do
    echo "🔁 Certificate changed → reloading nginx"
    nginx -s reload || true
done
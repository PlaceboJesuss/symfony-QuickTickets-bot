#!/bin/sh
set -e

CERT_PATH="/etc/letsencrypt/live/${APP_DOMAIN}/fullchain.pem"

echo ">>> Starting entrypoint..."
sleep 1

if [ ! -f "$CERT_PATH" ]; then
    echo ">>> No certificate found. Requesting initial cert..."

    certbot certonly \
        --webroot -w /var/www/certbot \
        --email "$APP_EMAIL" \
        --agree-tos \
        --no-eff-email \
        --non-interactive \
        -d "$APP_DOMAIN" \
        -d "www.$APP_DOMAIN"
else
    echo ">>> Certificate already exists."
fi

echo ">>> Starting renewal loop..."

while true; do
    certbot renew --webroot -w /var/www/certbot --quiet
    sleep 12h
done
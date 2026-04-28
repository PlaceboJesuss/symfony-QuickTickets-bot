#!/bin/sh
set -e

CERT_PATH="/etc/letsencrypt/live/${APP_DOMAIN}/fullchain.pem"

echo ">>> Starting entrypoint..."

# -----------------------------
# 1. INITIAL CERT (if missing)
# -----------------------------
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

# -----------------------------
# 2. START NGINX
# -----------------------------
echo ">>> Starting Nginx..."
nginx -g 'daemon off;' &

# -----------------------------
# 3. AUTO RENEW LOOP
# -----------------------------
echo ">>> Starting renewal loop..."

while true; do
    certbot renew --webroot -w /var/www/certbot --quiet

    # reload only if nginx is running
    if pgrep nginx >/dev/null; then
        nginx -s reload || true
    fi

    sleep 12h
done
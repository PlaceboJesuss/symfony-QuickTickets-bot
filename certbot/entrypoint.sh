#!/bin/sh
set -e

echo ">>> Starting certbot loop"

while true; do
  certbot renew --webroot -w /var/www/certbot --quiet
  sleep 12h
done
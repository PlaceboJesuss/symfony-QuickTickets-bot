#!/bin/sh
set -e

echo "🔧 Generating Nginx config"
envsubst '${APP_DOMAIN}' < /etc/nginx/conf.d/tmp.conf > /etc/nginx/conf.d/default.conf

echo "🚀 Starting nginx"
exec nginx -g 'daemon off;'
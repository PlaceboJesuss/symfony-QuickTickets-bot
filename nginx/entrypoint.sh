#!/bin/sh
set -e

echo "🔧 Generating Nginx config"
envsubst < ./conf.template > /etc/nginx/conf.d/default.conf

echo "🚀 Starting nginx"
exec nginx -g 'daemon off;'
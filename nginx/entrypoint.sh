#!/bin/sh
set -e

echo "🔧 Generating Nginx config"
if [ "$APP_ENV" = "prod" ]; then
  TEMPLATE=/etc/nginx/conf.d/prod.template
else
  TEMPLATE=/etc/nginx/conf.d/dev.template
fi

envsubst '${APP_DOMAIN}' < "$TEMPLATE" > /etc/nginx/conf.d/default.conf

echo "🚀 Starting nginx"
exec nginx -g 'daemon off;'
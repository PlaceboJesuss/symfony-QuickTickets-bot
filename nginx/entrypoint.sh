#!/bin/sh
set -e

echo "🔧 Generating Nginx config"

echo "🚀 Starting nginx"
exec nginx -g 'daemon off;'
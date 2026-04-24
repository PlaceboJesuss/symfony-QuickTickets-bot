#!/bin/bash
set -e
set -x

# -------------------
# Set permissions for storage and bootstrap/cache
# -------------------
#chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
#chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# -------------------
# Install Composer if not found
# -------------------
if ! command -v composer >/dev/null 2>&1; then
    echo "Composer not found — installing..."
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
fi

# -------------------
# Install Composer dependencies if missing
# -------------------
if [ ! -f /var/www/vendor/autoload.php ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --optimize-autoloader
else
    echo "Composer dependencies already installed"
fi

# -------------------
# Wait for MySQL to become available
# -------------------
#echo 'Waiting for MySQL via Laravel...'
#
#until php /var/www/artisan tinker --execute="DB::connection()->getPdo(); echo 'OK';" >/dev/null 2>&1; do
#    echo 'Waiting for MySQL...'
#    sleep 2
#done

# ----------------------------
# 1. Wait for DB (optional but useful)
# ----------------------------
if [ "$WAIT_FOR_DB" = "1" ]; then
  echo "⏳ Waiting for database..."
  until nc -z mariadb 3306; do
    sleep 1
  done
  echo "✅ Database is ready"
fi


# ----------------------------
# 2. Symfony cache warmup
# ----------------------------
echo "🧠 Clearing cache..."
php bin/console cache:clear --no-warmup || true

echo "🔥 Warming up cache..."
php bin/console cache:warmup || true

# ----------------------------
# 3. Fix permissions
# ----------------------------
chown -R www-data:www-data var || true


echo "📦 Running migrations..."
php bin/console doctrine:migrations:migrate --no-interaction || true

# Set all webhook (ONLY PROD via Symfony command)
# -------------------
if [ "$APP_ENV" = "prod" ]; then
  echo "🌍 Environment is PROD — running set-webhook command..."

  php bin/console app:set-webhook --no-interaction || {
    echo "⚠️ Failed to set webhook"
  }

else
  echo "🧪 Environment is NOT prod — skipping webhook setup"
fi


echo "Starting supervisord..."

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

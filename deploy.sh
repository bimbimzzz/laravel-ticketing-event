#!/bin/bash
set -e

echo "🚀 Deploying JagoEvent..."

# Pull latest code
echo "📥 Pulling latest changes..."
git pull origin JagoEvent

# Install/update PHP dependencies
echo "📦 Installing composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Run migrations
echo "🗄️ Running migrations..."
php artisan migrate --force

# Clear & rebuild caches
echo "⚡ Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Storage link
php artisan storage:link 2>/dev/null || true

# Set permissions
echo "🔒 Setting permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

# Restart queue worker (if using)
# php artisan queue:restart

echo "✅ Deploy complete!"

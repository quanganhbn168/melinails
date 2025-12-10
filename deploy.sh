#!/bin/bash

# ============================================
# CNETPOS DEPLOYMENT SCRIPT
# ============================================
# Usage: ./deploy.sh
# Run this on your production server
# ============================================

set -e  # Exit on any error

echo "🚀 Starting deployment..."

# 1. Pull latest code
echo "📥 Pulling latest code from GitHub..."
git pull origin main

# 2. Install dependencies (production only)
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader

# 3. Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# 4. Clear all caches
echo "🧹 Clearing caches..."
php artisan optimize:clear

# 5. Rebuild caches for production
echo "⚡ Building production caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Restart queue workers (if using)
# php artisan queue:restart

echo "✅ Deployment completed successfully!"
echo "📝 Remember to check the application in browser."

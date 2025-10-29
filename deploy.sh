#!/bin/bash
# Sport Academy MS - Quick Deploy Script
# Run this on Namecheap server after git pull

echo "🚀 Sport Academy MS - Deployment Script"
echo "========================================"
echo ""

# Navigate to project directory
cd ~/sportacademyms || { echo "❌ Error: Project directory not found"; exit 1; }

echo "📥 Pulling latest code from GitHub..."
git pull origin main

echo ""
echo "📦 Installing/Updating Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo ""
echo "🔧 Setting correct permissions..."
chmod -R 755 storage bootstrap/cache 2>/dev/null
find storage -type f -exec chmod 644 {} \; 2>/dev/null
find bootstrap/cache -type f -exec chmod 644 {} \; 2>/dev/null

echo ""
echo "🗄️ Running database migrations..."
php artisan migrate --force

echo ""
echo "🧹 Clearing all caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo ""
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "🔗 Creating storage link..."
php artisan storage:link 2>/dev/null || echo "   (Storage link already exists)"

echo ""
echo "✅ Deployment Complete!"
echo ""
echo "📊 System Status:"
echo "   PHP Version: $(php -v | head -n 1)"
echo "   Laravel Version: $(php artisan --version)"
echo ""
echo "🌐 Your site should now be live at:"
echo "   https://www.sportacademyms.app.avanciafitness.com"
echo ""
echo "💡 Tips:"
echo "   - Clear browser cache if styles don't load (Ctrl+Shift+R)"
echo "   - Check logs if errors occur: tail -50 storage/logs/laravel.log"
echo "   - Database credentials in .env file"
echo ""
echo "📞 Support: Gashumba | 0786 163 963 | info@sportacademyms.com"
